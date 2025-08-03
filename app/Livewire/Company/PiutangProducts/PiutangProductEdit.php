<?php

namespace App\Livewire\Company\PiutangProducts;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Models\Product;
use App\Repository\PiutangRepository;
use App\Repository\TransactionRepository;
use App\Service\PiutangService;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;

#[Layout('components.layouts.app')]
#[Title('Piutang Product Edit')]
class PiutangProductEdit extends Component
{
    use WithFileUploads;
    public Collection $allProducts;

    public Piutang $piutang;
    public array $piutangProducts = [];
    public $nomorFaktur;
    public $nomorOrder;
    public $userId;
    public $jumlahPiutang;
    public $ppn = 11;
    public $terms = 10;
    public $tanggalTransaction;
    public $tanggalJatuhTempo;
    public $customer;
    public $statusPembayaran;
    public $sisaHutang;
    public $buktiPembayaran;
    public $kodePiutang;
    public $subtotal = 0;
    public $ppnAmount = 0;
    public $proof;
    public $tanggalKirim;

    public bool $hasPayment = false;

    protected PiutangService $piutangService;
    public function boot(PiutangService $piutangService)
    {
        $this->piutangService = $piutangService;
    }

    protected function rules()
    {
        $id = $this->piutang->id;

        return [
            'nomorFaktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur,' . $id,
            'nomorOrder' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order,' . $id,
            'ppn' => 'required|integer|min:0|max:100',
            'terms' => 'required|integer|min:1|max:365',
            'tanggalTransaction' => 'required|date',
            'tanggalJatuhTempo' => 'required|date|after_or_equal:tanggalTransaction',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'piutangProducts' => 'required|array|min:1',
            'piutangProducts.*.product_id' => 'required|exists:products,id',
            'piutangProducts.*.qty' => 'required|integer|min:1',
            'piutangProducts.*.price' => 'required|numeric|min:1',
            'tanggalKirim' => 'nullable|date'
        ];
    }

    protected function messages()
    {
        return [
            'nomorFaktur.min' => 'Nomor faktur minimal 3 karakter.',
            'nomorFaktur.max' => 'Nomor faktur maksimal 100 karakter.',
            'nomorFaktur.unique' => 'Nomor faktur sudah digunakan.',
            'nomorOrder.min' => 'Nomor order minimal 3 karakter.',
            'nomorOrder.max' => 'Nomor order maksimal 100 karakter.',
            'nomorOrder.unique' => 'Nomor order sudah digunakan.',
            'ppn.required' => 'PPN wajib diisi.',
            'ppn.integer' => 'PPN harus berupa angka.',
            'ppn.min' => 'PPN tidak boleh negatif.',
            'ppn.max' => 'PPN maksimal 100%.',
            'terms.required' => 'Jangka waktu (terms) wajib diisi.',
            'terms.integer' => 'Terms harus berupa angka.',
            'terms.min' => 'Terms minimal 1 hari.',
            'terms.max' => 'Terms maksimal 365 hari.',
            'tanggalTransaction.required' => 'Tanggal transaksi wajib diisi.',
            'tanggalTransaction.date' => 'Tanggal transaksi tidak valid.',
            'tanggalJatuhTempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggalJatuhTempo.date' => 'Tanggal jatuh tempo tidak valid.',
            'tanggalJatuhTempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal transaksi.',
            'proof.file' => 'Bukti pembayaran harus berupa file.',
            'proof.mimes' => 'Bukti pembayaran harus JPG, PNG, atau PDF.',
            'proof.max' => 'Ukuran file maksimal 2MB.',
            'piutangProducts.required' => 'Setidaknya satu produk harus ditambahkan.',
            'piutangProducts.*.product_id.required' => 'Produk harus dipilih.',
            'piutangProducts.*.product_id.exists' => 'Produk tidak ditemukan.',
            'piutangProducts.*.qty.required' => 'Qty wajib diisi.',
            'piutangProducts.*.qty.integer' => 'Qty harus berupa angka.',
            'piutangProducts.*.qty.min' => 'Qty minimal 1.',
            'piutangProducts.*.price.required' => 'Harga produk wajib diisi.',
            'piutangProducts.*.price.numeric' => 'Harga harus berupa angka.',
            'piutangProducts.*.price.min' => 'Harga minimal Rp1.',
            'tanggalKirim.date' => 'Tanggal kirim tidak valid.',
        ];
    }

    public function mount(Piutang $piutang)
    {
        $piutang->load(['products']);
        $this->piutang = $piutang;
        $this->kodePiutang = $piutang->kode_piutang;
        $this->userId = $piutang->user_id;
        $this->nomorFaktur = $piutang->nomor_faktur;
        $this->nomorOrder = $piutang->nomor_order;
        $this->customer = $piutang->user->name;
        $this->jumlahPiutang = $piutang->jumlah_piutang;
        $this->ppn = $piutang->ppn;
        $this->tanggalTransaction = $piutang->tanggal_transaction;
        $this->tanggalJatuhTempo = $piutang->tanggal_jatuh_tempo;
        $this->sisaHutang = $piutang->sisa_piutang;
        $this->statusPembayaran = $piutang->status_pembayaran;
        $this->buktiPembayaran = $piutang->bukti_pembayaran;
        $this->tanggalKirim = $piutang->tanggal_kirim;


        if ($piutang->products->count() > 0) {
            foreach ($piutang->products as $key => $product) {
                $this->piutangProducts[] = [
                    'product_id' => $product->id,
                    'qty' => $product->pivot->qty,
                    'price' => $product->pivot->price,
                ];
            }
        } else {
            $this->piutangProducts[] = [];
        }
        $this->hasPayment = $piutang->paymentPiutangs()->exists() || $piutang->status_pembayaran === StatusType::SUCCESS->value; // lebih efisien daripada count() > 0
        $this->allProducts = Product::select('id', 'name')->get();
    }

    public function update()
    {
        try {
            $this->calculateTotal();
            if ($this->piutang->status_pembayaran === StatusType::SUCCESS->value) {
                // Jangan ubah sisa hutang kalau status sudah SUCCESS
                $this->sisaHutang = 0;
            } else {
                $this->sisaHutang = $this->jumlahPiutang;
            }
            $data = [
                'terms' => $this->terms,
                'tanggal_transaction' => $this->tanggalTransaction,
                'tanggal_jatuh_tempo' => $this->tanggalJatuhTempo,
                'jumlah_piutang' => $this->jumlahPiutang,
                'nomor_faktur' => $this->nomorFaktur,
                'nomor_order' => $this->nomorOrder,
                'ppn' => $this->ppn,
                'sisa_piutang' => $this->sisaHutang,
                'status_pembayaran' => $this->statusPembayaran,
                'bukti_pembayaran' => $this->proof,
                'tanggal_kirim' => $this->tanggalKirim
            ];

            if ($this->proof) {
                $data['bukti_pembayaran'] = $this->proof;
            }

            $this->piutangService->updatePiutangProduct(
                $this->piutang,
                $this->piutangProducts,
                $data
            );

            $this->redirect(PiutangProducts::class);
            session()->flash('success', 'Piutang updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Piutang update failed.');
            throw $e;
        }
    }


    public function addProduct()
    {
        $this->piutangProducts[] = [
            'product_id' => '',
            'qty' => 0,
            'price' => 0,
        ];
        $this->calculateTotal();
    }

    public function removeProduct($index)
    {
        unset($this->piutangProducts[$index]);
        $this->piutangProducts = array_values($this->piutangProducts);
    }


    public function updatedPiutangProducts()
    {

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal  = 0;
        foreach ($this->piutangProducts as $product) {
            if (!empty($product['qty']) && !empty($product['price'])) {
                $this->subtotal  += (int) $product['qty'] * (int) $product['price'];
            }
        }

        $this->ppnAmount = ($this->ppn / 100) * $this->subtotal;
        $this->jumlahPiutang = $this->subtotal + $this->ppnAmount;
    }

    #[Computed()]
    public function products($index)
    {
        $selected = collect($this->piutangProducts)
            ->pluck('product_id')
            ->filter()
            ->toArray();


        $currentSelectedId = $this->piutangProducts[$index]['product_id'] ?? null;
        $selected = array_filter($selected, fn($id) => $id != $currentSelectedId);
        $query = Product::whereNotIn('id', $selected);
        return $query->get();
    }


    public function render()
    {
        return view('livewire.company.piutang-products.piutang-product-edit');
    }
}
