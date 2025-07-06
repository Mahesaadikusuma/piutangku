<?php

namespace App\Service;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Repository\PiutangRepository;
use App\Repository\TransactionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class PiutangService
{

    protected PiutangRepository $piutangRepository;
    protected TransactionRepository $transactionRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(
        PiutangRepository $piutangRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->piutangRepository = $piutangRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function updatePiutang(Piutang $piutang, array $data): void
    {
        if (
            $piutang->status_pembayaran === StatusType::SUCCESS->value &&
            $data['status_pembayaran'] !== $piutang->status_pembayaran
        ) {
            throw new Exception('Status tidak bisa diubah setelah SUCCESS.');
        }

        if (
            $piutang->status_pembayaran === StatusType::SUCCESS->value && isset($data['sisa_piutang']) && $data['sisa_piutang'] > 0
        ) {
            $data['sisa_piutang'] = 0;
        }

        // ✅ Jika piutang memiliki relasi produk, hapus semua (karena ini piutang biasa)
        if ($piutang->products()->exists()) {
            $piutang->products()->detach(); // hapus semua relasi di tabel pivot
        }

        if (isset($data['bukti_pembayaran'])) {
            $publicDisk = Storage::disk('public');

            if ($piutang->bukti_pembayaran && $publicDisk->exists($piutang->bukti_pembayaran)) {
                $publicDisk->delete($piutang->bukti_pembayaran);
            }

            $data['bukti_pembayaran'] = $data['bukti_pembayaran']->storeAs(
                'piutangs/proof',
                $data['bukti_pembayaran']->hashName(),
                'public'
            );
        }

        $isBecomingSuccess = $piutang->status_pembayaran !== StatusType::SUCCESS->value &&
            $data['status_pembayaran'] === StatusType::SUCCESS->value;

        if ($isBecomingSuccess) {
            $this->transactionRepository->createTransaction([
                'user_id' => $piutang->user_id,
                'transaction_total' => $data['sisa_piutang'],
                'status' => StatusType::SUCCESS->value,
                'piutang_id' => $piutang->id
            ]);

            $data['sisa_piutang'] = 0;
            $data['tanggal_lunas'] = Carbon::now();
            $data['status_pembayaran'] = StatusType::SUCCESS->value;
        }


        $this->piutangRepository->update($piutang, $data);
    }


    public function updatePiutangProduct(Piutang $piutang, array $piutangProducts, array $data): void
    {
        if (
            $piutang->status_pembayaran === StatusType::SUCCESS->value &&
            $data['status_pembayaran'] !== $piutang->status_pembayaran
        ) {
            throw new Exception('Status tidak bisa diubah setelah SUCCESS.');
        }
        if (
            $piutang->status_pembayaran === StatusType::SUCCESS->value &&
            isset($data['sisa_piutang']) &&
            $data['sisa_piutang'] > 0
        ) {
            $data['sisa_piutang'] = 0;
        }

        $originalProducts = $piutang->products->mapWithKeys(fn($product) => [
            $product->pivot->product_id => [
                'qty' => $product->pivot->qty,
                'price' => $product->pivot->price,
            ]
        ])->toArray();

        $updatedProducts = collect($piutangProducts)->mapWithKeys(fn($product) => [
            $product['product_id'] => [
                'qty' => $product['qty'],
                'price' => $product['price'],
            ]
        ])->toArray();

        $productChanged = $originalProducts != $updatedProducts;

        if ($productChanged && $piutang->paymentPiutangs()->exists()) {
            throw new Exception('Tidak bisa mengubah produk karena piutang sudah memiliki pembayaran.');
        }

        if ($productChanged) {
            $piutang->products()->sync($updatedProducts);
        }

        if (isset($data['bukti_pembayaran'])) {
            $publicDisk = Storage::disk('public');

            if ($piutang->bukti_pembayaran && $publicDisk->exists($piutang->bukti_pembayaran)) {
                $publicDisk->delete($piutang->bukti_pembayaran);
            }

            $data['bukti_pembayaran'] = $data['bukti_pembayaran']->storeAs(
                'piutangs/proof',
                $data['bukti_pembayaran']->hashName(),
                'public'
            );
        }

        $isBecomingSuccess = $piutang->status_pembayaran !== StatusType::SUCCESS->value &&
            $data['status_pembayaran'] === StatusType::SUCCESS->value;

        if ($isBecomingSuccess) {
            $this->transactionRepository->createTransaction([
                'user_id' => $piutang->user_id,
                'transaction_total' => $data['sisa_piutang'],
                'status' => StatusType::SUCCESS->value,
                'piutang_id' => $piutang->id
            ]);

            $data['sisa_piutang'] = 0;
            $data['tanggal_lunas'] = Carbon::now();
            $data['status_pembayaran'] = StatusType::SUCCESS->value;
        }


        $this->piutangRepository->update($piutang, $data);
    }
}
