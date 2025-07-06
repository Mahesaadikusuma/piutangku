<?php

namespace App\Livewire\Company\Transactions;

use App\Enums\StatusType;
use App\Models\Customer;
use App\Models\PaymentPiutang;
use App\Models\Piutang;
use App\Repository\Interface\TransactionInterface;
use App\Repository\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Transaction Create')]
class PaymentCreate extends Component
{
    #[Validate]
    public array $transactionPiutangs = [];
    public $transactionTotal;
    public $jenisPembayaran = null;
    public $lockedUserId = null;

    protected TransactionInterface $TransactionRepository;

    public function boot(TransactionInterface $transactionRepository)
    {
        $this->TransactionRepository = $transactionRepository;
    }

    protected function rules()
    {
        return [
            'lockedUserId' => 'required|exists:users,id',
            'transactionTotal' => 'required|numeric|min:1',
            'jenisPembayaran' => 'nullable|string|max:100',
            'transactionPiutangs' => 'required|array|min:1',
            'transactionPiutangs.*.piutang_id' => 'required|exists:piutangs,id',
            'transactionPiutangs.*.amount' => 'required|numeric|min:1',
        ];
    }
    protected function messages()
    {
        return [
            'lockedUserId.required' => 'User belum ditentukan dari piutang.',
            'lockedUserId.exists' => 'User tidak valid.',
            'transactionTotal.required' => 'Total transaksi wajib diisi.',
            'transactionTotal.numeric' => 'Total transaksi harus berupa angka.',
            'transactionTotal.min' => 'Total transaksi minimal Rp1.',
            'jenisPembayaran.string' => 'Jenis pembayaran harus berupa teks.',
            'transactionPiutangs.required' => 'Setidaknya satu piutang harus ditambahkan.',
            'transactionPiutangs.*.piutang_id.required' => 'Piutang wajib dipilih.',
            'transactionPiutangs.*.piutang_id.exists' => 'Piutang yang dipilih tidak ditemukan.',
            'transactionPiutangs.*.amount.required' => 'Jumlah pembayaran wajib diisi.',
            'transactionPiutangs.*.amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'transactionPiutangs.*.amount.min' => 'Jumlah pembayaran minimal Rp1.',
        ];
    }


    public function mount()
    {
        $this->transactionPiutangs[] = [
            'piutang_id' => '',
            'jumlah_hutang' => 0,
            'sisa_hutang' => 0,
            'amount' => 0
        ];
    }

    public function store()
    {
        $this->validate();
        if (!$this->lockedUserId) {
            $this->addError('lockedUserId', 'User belum ditentukan dari piutang.');
            return;
        }

        DB::beginTransaction();

        try {
            $data = [
                'user_id' => $this->lockedUserId,
                'transaction_total' => $this->transactionTotal,
                'jenis_pembayaran' => $this->jenisPembayaran,
                'piutang_id' => $this->transactionPiutangs[0]['piutang_id']
            ];
            $transaction = $this->TransactionRepository->createTransaction($data);

            foreach ($this->transactionPiutangs as $index => $item) {
                if ($item['amount'] > $item['sisa_hutang']) {
                    // Log::info("Jumlah Pembayaran melebihi sisa piutang");
                    $this->addError('transactionPiutangs.' . $index . '.amount', 'Jumlah Pembayaran melebihi sisa piutang');
                    return;
                }

                PaymentPiutang::create([
                    'transaction_id' => $transaction->id,
                    'piutang_id' => $item['piutang_id'],
                    'amount' => $item['amount'],
                ]);
            }

            DB::commit();
            $this->reset();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addPiutang()
    {
        $this->transactionPiutangs[] = [
            'piutang_id' => '',
            'jumlah_hutang' => 0,
            'sisa_hutang' => 0,
            'amount' => 0
        ];
    }

    public function removePiutang($index)
    {
        unset($this->transactionPiutangs[$index]);
        $this->transactionPiutangs = array_values($this->transactionPiutangs);

        // Reset lockedUserId jika tidak ada piutang lagi
        if (empty($this->transactionPiutangs)) {
            $this->lockedUserId = null;
        }

        $this->calculateTotal();
    }

    public function updatedTransactionPiutangs()
    {
        foreach ($this->transactionPiutangs as $index => $item) {
            if (!empty($item['piutang_id'])) {
                $piutang = Piutang::with('user')->find($item['piutang_id']);
                if ($piutang) {
                    if (is_null($this->lockedUserId)) {
                        $this->lockedUserId = $piutang->user_id;
                    }
                    $this->transactionPiutangs[$index]['jumlah_hutang'] = $piutang->jumlah_piutang;
                    $this->transactionPiutangs[$index]['sisa_hutang'] = $piutang->sisa_piutang;
                }
            }
        }

        $this->transactionPiutangs = array_values($this->transactionPiutangs);
        $this->calculateTotal();
    }


    public function calculateTotal()
    {
        $this->transactionTotal = collect($this->transactionPiutangs)
            ->sum(fn($item) => floatval($item['amount']));
    }

    #[Computed()]
    public function piutangs($index)
    {
        $selected = collect($this->transactionPiutangs)
            ->pluck('piutang_id')
            ->filter()
            ->toArray();

        $currentSelectedId = $this->transactionPiutangs[$index]['piutang_id'] ?? null;

        $selected = array_filter($selected, fn($id) => $id != $currentSelectedId);

        $query = Piutang::where('status_pembayaran', '!=', StatusType::SUCCESS->value)
            ->whereNotIn('id', $selected);

        // Filter hanya piutang dari user_id yang sudah dikunci
        if ($this->lockedUserId) {
            $query->where('user_id', $this->lockedUserId);
        }

        return $query->get();
    }



    public function render()
    {
        return view('livewire.company.transactions.payment-create');
    }
}
