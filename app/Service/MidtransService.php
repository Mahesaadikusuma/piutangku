<?php

namespace App\Service;

use App\Livewire\Company\Transactions\Transactions;
use App\Models\Transaction;
use App\Repository\PiutangRepository;
use App\Repository\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransService
{
    protected $transactionRepository;
    protected $piutangRepository;


    /**
     * Create a new class instance.
     */
    public function __construct(TransactionRepository $transactionRepository, PiutangRepository $piutangRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->piutangRepository = $piutangRepository;
        $this->configureMidtrans();
    }

    private function configureMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function transactionPayment($transaction)
    {
        DB::beginTransaction();
        try {
            if ($transaction->midtrans_url) {
                return redirect($transaction->midtrans_url);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->kode,
                    'gross_amount' => $transaction->transaction_total
                ],
                'customer_details' => [
                    'first_name' => $transaction?->user?->setting->full_name,
                    'email' => $transaction?->user->email,
                    'phone' => $transaction?->user?->setting->phone_number,
                ],
                'item_details' => $transaction->paymentPiutangs->map(function ($payment) {
                    return [
                        'id' => 'payment-' . $payment->id,
                        'price' => $payment->amount,
                        'quantity' => 1,
                        'name' => 'Pembayaran Piutang #'  . ($payment->piutang->kode_piutang ?? 'Unknown'),
                    ];
                })->toArray(),
            ];

            $snapToken = Snap::createTransaction($params)->redirect_url;
            $transaction->update([
                'midtrans_url' => $snapToken
            ]);
            DB::commit();

            return redirect($snapToken);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Error Piutang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function processPaymentPiutang($piutang)
    {
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $piutang->user_id,
                'transaction_total' => $piutang->sisa_piutang,
                'jenis_pembayaran' => 'MIDTRANS',
                'piutang_id' => $piutang->id,
            ];
            $transaction = $this->transactionRepository->createTransaction($data);

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->kode,
                    'gross_amount' => $piutang->sisa_piutang
                ],
                'customer_details' => [
                    'first_name' => $piutang?->user?->setting->full_name,
                    'email' => $transaction?->user->email,
                    'phone' => $transaction?->user?->setting->phone_number,
                ],
                'item_details' => [
                    [
                        'id' => 'piutangs-' . $piutang->uuid,
                        'price' => $piutang->sisa_piutang,
                        'quantity' => 1,
                        'name' => 'Pembayaran Piutang #' . $piutang->kode_piutang,
                    ]
                ],
            ];
            // Log::info($params);

            // Snap URL
            $snapToken = Snap::createTransaction($params)->redirect_url;
            Log::info($snapToken);
            $transaction->update([
                'midtrans_url' => $snapToken
            ]);
            DB::commit();

            return redirect($snapToken);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Error Piutang: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleWebHookNotification(Request $request)
    {
        $notification = new Notification();
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order = $notification->order_id;
        Log::info("Transaction status info", [
            'status' => $status,
            'type' => $type,
            'fraud' => $fraud,
            'order' => $order,
        ]);



        $serverKey = Config::$serverKey = config('midtrans.server_key');
        $signatureKey = hash(
            "sha512",
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey
        );
        Log::info("signature key :", ['signature' => $signatureKey]);
        if ($signatureKey !== $request->signature_key) {
            Log::error('Invalid signature key');
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transaction = Transaction::with(['paymentPiutangs', 'user', 'piutang'])
            ->where('kode', $order)
            ->first();

        if (!$transaction) {
            Log::info('payment not found');
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $piutang = $transaction->piutang;
        $bankName = null;

        if ($type === 'bank_transfer' && isset($request->va_numbers[0]['bank'])) {
            $bankName = strtoupper($request->va_numbers[0]['bank']);
            Log::info('VA Numbers:', ['va_numbers' => $request->va_numbers]);
        }

        if ($status == 'capture' && $type == 'credit_card') {
            if ($fraud == 'challenge') {
                $transaction->status = 'CHALLENGE';
            } else {
                $transaction->status = 'Success';
            }
        } elseif ($status == 'settlement' || $status == 'capture') {
            $transaction->status = 'Success';
        } elseif ($status == 'cancel' || $status == 'expire') {
            $transaction->status = 'Failed';
            $piutang->status_pembayaran = 'Pending';
        } elseif ($status == 'pending') {
            $transaction->status = 'Pending';
            $piutang->status_pembayaran = 'Pending';
        }

        // Simpan Jenis Pembayaran
        $transaction->jenis_pembayaran = ($type === 'bank_transfer' && $bankName) ? "Midtrans-$type-$bankName" : $type;

        if ($status == 'settlement' || ($status == 'capture' && $fraud != 'challenge')) {
            // Log::info('Sisa Hutang:', ['transaction Total' => $transaction->transaction_total]);
            $sisaHutang = ($piutang->sisa_piutang - $transaction->transaction_total);
            // Log::info('Sisa Hutang:', ['sisa_hutang' => $sisaHutang]);
            $piutang->sisa_piutang = $sisaHutang;
            // Log::info('Sisa Hutang:', ['sisa_piutang' => $piutang->sisa_piutang]);

            if ($sisaHutang === 0.0) {
                $piutang->status_pembayaran = 'Success';
                $piutang->tanggal_lunas = Carbon::now();
            } else {
                $piutang->status_pembayaran = 'Pending';
            }
        }

        $transaction->save();
        $piutang->save();

        if (in_array($status, ['capture', 'settlement', 'success'])) {
            // event(new PaymentCompleted($transaction));
        } elseif ($status == 'capture' && $fraud == 'challenge') {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans Payment Challenge',
                ],
            ]);
        } else {
            Log::info('error midtrans');
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Midtrans payment not settlement'
                ]
            ]);
        }
        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
