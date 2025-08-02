<?php

namespace App\Livewire\Company\Piutangs;

use App\Livewire\Forms\PiutangMouForm;
use App\Models\Piutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Piutang Mou')]
class PiutangMou extends Component
{
    use WithFileUploads;

    public PiutangMouForm $form;
    public Piutang $piutang;

    public function mount(Piutang $piutang)
    {
        $this->piutang = $piutang;
        $this->form->setPiutang($piutang);
    }

    public function save()
    {
        try {
            $this->form->save();
            $this->form->setPiutang($this->piutang);
            session()->flash('success', 'Piutang MOU berhasil.');
            // $this->dispatch('formUpdated');
            $this->redirectRoute('master-data.piutang.mou', ['piutang' => $this->piutang->uuid]);
        } catch (\Exception $e) {
            session()->flash('error', 'Piutang MOU gagal.');
            Log::error("Error: " . $e->getMessage());
            return back();
        }
    }

    public function downloadPdf()
    {
        $pdf = Pdf::loadView('pdf.piutang-mou', ['piutang' => $this->piutang, 'agreement' => $this->form->agreement]);

        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, 'Mou.pdf');
    }

    public function render()
    {
        return view('livewire.company.piutangs.piutang-mou');
    }
}
