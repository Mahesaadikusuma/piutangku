<?php

namespace App\Livewire\Customers\Transactions;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('History Transactions')]
class TransactionDetailCustomer extends Component
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
        return view('livewire.customers.transactions.transaction-detail-customer');
    }
}
