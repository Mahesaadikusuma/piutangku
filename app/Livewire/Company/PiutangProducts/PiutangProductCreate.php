<?php

namespace App\Livewire\Company\PiutangProducts;

use App\Models\Customer;
use App\Models\Product;
use App\Repository\Interface\PiutangInterface;
use App\Repository\PiutangRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Piutang Product Create')]
class PiutangProductCreate extends Component
{
    #[Validate()]
    public array $piutangProducts = [];
    public $jumlahPiutang;
    public $userId;
    public $nomorFaktur;
    public $nomorOrder;
    public $ppn = 11;
    public $terms = 10;
    public $tanggalTransaction;
    public $tanggalJatuhTempo;
    public $subtotal = 0;
    public $ppnAmount = 0;

    protected PiutangInterface $piutangRepository;
    public function boot(PiutangInterface $piutangRepository)
    {
        $this->piutangRepository = $piutangRepository;
    }

    protected function rules()
    {
        return [
            'userId' => 'required|exists:users,id',
            'nomorFaktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur',
            'nomorOrder' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order',
            'ppn' => 'required|integer|min:0|max:100',
            'terms' => 'required|integer|min:1|max:1000',
            'tanggalTransaction' => 'required|date',
            'tanggalJatuhTempo' => 'required|date|after_or_equal:tanggalTransaction',
            'piutangProducts' => 'required|array|min:1',
            'piutangProducts.*.product_id' => 'required|exists:products,id',
            'piutangProducts.*.qty' => 'required|integer|min:1',
            'piutangProducts.*.price' => 'required|numeric|min:1',
        ];
    }

    protected function messages()
    {
        return [
            'userId.required' => 'Customer wajib dipilih.',
            'userId.exists' => 'Customer tidak valid.',
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
            'piutangProducts.required' => 'Setidaknya satu produk harus ditambahkan.',
            'piutangProducts.*.product_id.required' => 'Produk harus dipilih.',
            'piutangProducts.*.product_id.exists' => 'Produk tidak ditemukan.',
            'piutangProducts.*.qty.required' => 'Qty wajib diisi.',
            'piutangProducts.*.qty.integer' => 'Qty harus berupa angka.',
            'piutangProducts.*.qty.min' => 'Qty minimal 1.',
            'piutangProducts.*.price.required' => 'Harga produk wajib diisi.',
            'piutangProducts.*.price.numeric' => 'Harga harus berupa angka.',
            'piutangProducts.*.price.min' => 'Harga minimal Rp1.',
        ];
    }

    public function mount()
    {
        $this->piutangProducts[] = [
            'product_id' => '',
            'qty' => 0,
            'price' => 0,
        ];

        $this->tanggalTransaction = Carbon::now()->format('Y-m-d');
        $this->tanggalJatuhTempo = Carbon::now()->addDays($this->terms)->format('Y-m-d');
    }


    public function store()
    {
        DB::beginTransaction();
        $this->validate();
        try {
            $dataPiutang = [
                'user_id' => $this->userId,
                'nomor_faktur' => $this->nomorFaktur,
                'nomor_order' => $this->nomorOrder,
                'jumlah_piutang' => $this->jumlahPiutang,
                'ppn' => $this->ppn,
                'terms' => $this->terms,
                'tanggal_transaction' => $this->tanggalTransaction,
                'tanggal_jatuh_tempo' => $this->tanggalJatuhTempo,
            ];
            $piutang = $this->piutangRepository->createPiutang($dataPiutang);
            foreach ($this->piutangProducts as $key => $product) {
                $piutang->products()->attach($product['product_id'], ['qty' => $product['qty'], 'price' => $product['price']]);

                $productModel = Product::find($product['product_id']);
                if ($productModel) {
                    if ($productModel->stock < $product['qty']) {
                        // $this->addError('piutangProducts', 'Stock product ' . $productModel->name . ' tidak mencukupi.');
                        $this->addError('piutangProducts.' . $key . '.qty', 'Stock produk ' . $productModel->name . ' tidak mencukupi.');
                        return;
                    }

                    $productModel->decrement('stock', $product['qty']);
                }
            }
            DB::commit();
            $this->redirect(PiutangProducts::class);
            session()->flash('success', 'Piutang created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Piutang created failed.');
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

    #[Computed()]
    public function customers()
    {
        return Customer::with(['user'])->select('id', 'code_customer', 'user_id')->get();
    }

    public function render()
    {
        return view('livewire.company.piutang-products.piutang-product-create');
    }
}
