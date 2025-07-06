<?php

namespace App\Livewire\Customers\Transactions;

use App\Models\Transaction;
use App\Service\MidtransService;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Transaction Payment')]
class TransactionPayment extends Component
{
    public Transaction $transaction;
    public $transactionTotal;

    protected MidtransService $midtransService;
    public function boot(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionTotal = number_format($transaction->transaction_total);
    }

    public function payment()
    {
        try {
            $this->midtransService->transactionPayment($this->transaction);
        } catch (Exception $e) {
            session()->now('error', 'Payment failed.' . $e->getMessage());
            return redirect()->route('transactions.customer.trnsaction');
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.customers.transactions.transaction-payment');
    }
}
