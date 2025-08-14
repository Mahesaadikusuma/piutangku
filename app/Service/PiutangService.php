<?php

namespace App\Service;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Models\Product;
use App\Repository\PiutangRepository;
use App\Repository\TransactionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        DB::beginTransaction();

        try {
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
                // 1. Cek stok terlebih dahulu sebelum sync
                foreach ($updatedProducts as $productId => $dataProduct) {
                    $productModel = Product::find($productId);
                    if (!$productModel) {
                        throw new Exception('Produk tidak ditemukan.');
                    }

                    $oldQty = $originalProducts[$productId]['qty'] ?? 0;
                    $newQty = $dataProduct['qty'];
                    $qtyChange = $newQty - $oldQty;

                    if ($qtyChange > 0) {
                        // Konversi stok ke integer
                        $stock = (int) str_replace(',', '', $productModel->stock);

                        if ($stock < $qtyChange) {
                            throw new Exception("Stock produk {$productModel->name} tidak mencukupi untuk update.");
                        }
                    }
                }

                // 2. Sync produk
                $piutang->products()->sync($updatedProducts);

                // 3. Update stok manual
                foreach ($updatedProducts as $productId => $dataProduct) {
                    $productModel = Product::find($productId);
                    $oldQty = $originalProducts[$productId]['qty'] ?? 0;
                    $newQty = $dataProduct['qty'];

                    $stock = (int) str_replace(',', '', $productModel->stock);

                    // jumlah produk yang baru lebih besar daripada yang lama
                    // $newQty - $oldQty = 5 - 2 = 3
                    if ($newQty > $oldQty) {
                        $newStock = $stock - ($newQty - $oldQty);

                        // jumlah produk baru lebih kecil daripada yang lama → kita harus mengembalikan stok ke gudang
                    } elseif ($newQty < $oldQty) {
                        $newStock = $stock + ($oldQty - $newQty);
                        // tidak ada perubahan qty (qty baru sama dengan lama), stok tetap
                    } else {
                        $newStock = $stock;
                    }

                    $productModel->update(['stock' => $newStock]);
                }
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Piutang Error', ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
