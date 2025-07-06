<?php

namespace App\Livewire\Company\Piutangs;

use App\Livewire\Forms\PiutangForm;
use App\Models\Customer;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Piutang Create')]
class PiutangCreate extends Component
{
    public PiutangForm $form;

    public function mount()
    {
        $this->form->setPiutangCreate();
    }

    public function store()
    {
        $this->form->store();
        $this->redirect(Piutangs::class);
    }

    public function updatedFormJumlahFaktur($value)
    {
        $this->hitungJumlahPpn();
    }

    public function updatedFormPpn($value)
    {
        $this->hitungJumlahPpn();
    }

    public function hitungJumlahPpn()
    {
        $jumlah = floatval($this->form->jumlahFaktur ?? 0);
        $ppn = floatval($this->form->ppn ?? 0);

        $this->form->jumlahPpn = round($jumlah * ($ppn / 100), 2);
        $this->form->grandTotal = $jumlah + $this->form->jumlahPpn;
    }


    #[Computed()]
    public function customers()
    {
        return Customer::with(['user'])->select('id', 'code_customer', 'user_id')->get();
    }

    public function render()
    {
        return view('livewire.company.piutangs.piutang-create');
    }
}
