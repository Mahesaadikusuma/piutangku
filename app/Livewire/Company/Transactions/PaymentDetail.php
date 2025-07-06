<?php

namespace App\Livewire\Company\Transactions;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Payment Transaction Detail')]
class PaymentDetail extends Component
{
    public Transaction $transaction;
    public string $kode;

    public function mount(Transaction $transaction): void
    {
        $this->transaction = $transaction;
        $this->kode = $transaction->kode;
    }

    public function render()
    {
        $payments = $this->transaction->paymentPiutangs()->paginate(10);
        return view('livewire.company.transactions.payment-detail', [
            'payments' => $payments,
        ]);
    }
}
