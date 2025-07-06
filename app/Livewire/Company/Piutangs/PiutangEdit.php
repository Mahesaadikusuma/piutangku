<?php

namespace App\Livewire\Company\Piutangs;

use App\Livewire\Forms\PiutangForm;
use App\Models\Piutang;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Piutang Edit')]
class PiutangEdit extends Component
{
    use WithFileUploads;
    public PiutangForm $form;
    public Piutang $piutang;

    public function mount(Piutang $piutang)
    {
        $this->form->setPiutang($piutang);
    }

    public function update()
    {
        $this->form->update();
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

    public function render()
    {
        return view('livewire.company.piutangs.piutang-edit');
    }
}
