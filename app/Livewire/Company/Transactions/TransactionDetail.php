<?php

namespace App\Livewire\Company\Transactions;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Transaction Detail')]
class TransactionDetail extends Component
{
    public Transaction $transaction;
    public $transactionTotal;
    public $status;
    public $jenisPembayaran;

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionTotal = number_format($transaction->transaction_total);
        $this->status = $transaction->status;
        $this->jenisPembayaran = $transaction->jenis_pembayaran ?? '-';
    }

    public function render()
    {
        return view('livewire.company.transactions.transaction-detail');
    }
}
