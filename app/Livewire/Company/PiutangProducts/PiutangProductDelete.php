<?php

namespace App\Livewire\Company\PiutangProducts;

use App\Livewire\Forms\PiutangForm;
use App\Models\Piutang;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Piutang Delete')]
class PiutangProductDelete extends Component
{
    public PiutangForm $form;
    public Piutang $piutang;

    #[On('piutangDelete')]
    public function deletePiutang($id)
    {
        $this->piutang = Piutang::find($id);
        $this->form->setPiutang($this->piutang);
        Flux::modal('delete-piutang')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-piutang')->close();
            $this->dispatch('reloadPiutangs');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.piutang-products.piutang-product-delete');
    }
}
