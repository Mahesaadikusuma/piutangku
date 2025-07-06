<?php

namespace App\Livewire\Company;

use App\Models\Customer;
use App\Models\Piutang;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Customer Age Piutang')]
class CustomerAgePiutangDetail extends Component
{
    use WithPagination;

    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function render()
    {
        $piutangs = Piutang::where('user_id', $this->customer->user_id)->with(['user', 'agreement', 'paymentPiutangs'])->paginate(10);
        return view('livewire.company.customer-age-piutang-detail', [
            'piutangs' => $piutangs
        ]);
    }
}
