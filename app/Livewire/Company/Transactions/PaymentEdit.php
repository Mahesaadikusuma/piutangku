<?php

namespace App\Livewire\Company\Transactions;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Transaction Edit')]
class PaymentEdit extends Component
{
    use WithFileUploads;
    public Transaction $transaction;

    #[Validate]
    public $transactionTotal;
    public $jenisPembayaran = null;
    public $proof = null;
    public $status;

    public $error;

    protected function rules()
    {
        return [
            'transactionTotal'   => 'required|numeric|min:1',
            'jenisPembayaran'    => 'nullable|string|max:50',
            'proof'              => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status'             => 'required|in:Pending,Success,Failed',
        ];
    }

    protected function messages()
    {
        return [
            'transactionTotal.required' => 'Total transaksi wajib diisi.',
            'transactionTotal.numeric' => 'Total transaksi harus berupa angka.',
            'transactionTotal.min' => 'Total transaksi minimal Rp1.',

            'jenisPembayaran.string' => 'Jenis pembayaran harus berupa teks.',
            'jenisPembayaran.max' => 'Jenis pembayaran maksimal 50 karakter.',

            'proof.file' => 'Bukti pembayaran harus berupa file.',
            'proof.mimes' => 'Bukti pembayaran harus berupa file JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran bukti pembayaran maksimal 2MB.',

            'status.required' => 'Status transaksi wajib dipilih.',
            'status.in' => 'Status transaksi tidak valid.',
        ];
    }


    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionTotal = $transaction->transaction_total;
        $this->jenisPembayaran = $transaction->jenis_pembayaran;
        $this->status = $transaction->status;
    }


    public function update()
    {
        try {
            $this->validate();
            // Jika transaksi sudah success, jangan izinkan update
            if ($this->transaction->status === StatusType::SUCCESS->value) {
                $this->addError('status', 'Transaksi dengan status "Success" tidak dapat diubah.');
                return;
            }

            // Jika status yang dipilih saat ini adalah success, maka baru kurangi sisa hutang
            if ($this->status === StatusType::SUCCESS->value) {
                foreach ($this->transaction->paymentPiutangs as $payment) {
                    $piutang = Piutang::find($payment->piutang_id);

                    // Pastikan piutang belum lunas agar tidak dikurangi ulang
                    if ($piutang->status_pembayaran !== StatusType::SUCCESS->value) {
                        $piutang->sisa_piutang -= $payment->amount;

                        if ($piutang->sisa_piutang <= 0) {
                            $piutang->sisa_piutang = 0;
                            $piutang->status_pembayaran = StatusType::SUCCESS->value;
                            $piutang->tanggal_lunas = Carbon::now();
                        }

                        $piutang->save();
                    }
                }
            }

            // Update transaksi
            $this->transaction->update([
                'transaction_total' => $this->transactionTotal,
                'proof' => $this->proof ? $this->proof->store('transaction/proof', 'public') : $this->transaction->proof,
                'jenis_pembayaran' => $this->jenisPembayaran,
                'status' => $this->status
            ]);

            // session()->flash('success', 'Transaction updated successfully.');
            $this->dispatch('payment-updated');
        } catch (\Exception $e) {
            $this->error = 'Transaction update failed' . $e->getMessage();
            // session()->flash('error', 'Transaction update failed.' . $e->getMessage());
            $this->dispatch('error');
            return redirect()->back();
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.transactions.payment-edit');
    }
}
