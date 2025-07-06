<?php

namespace App\Livewire\Forms;

use App\Models\Piutang;
use App\Models\PiutangAgreement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class PiutangMouForm extends Form
{
    use WithFileUploads;

    public ?int $piutang_id = null;
    public ?int $agreement_id = null;

    #[Validate()]
    public $nomorDokument;
    public $lampiran = "-";
    public $perihal;
    public $leadCompany = "PT. Tayoh Sarana Sukses";
    public $leadName;
    public $leadPoss;
    public $browCompany;
    public $browName;
    public $browAddress;
    public $browPoss;
    public $agreeDate;
    public $content;
    public $generatePdf;

    public $agreement;


    protected function rules()
    {
        return [
            'nomorDokument' => 'required|string|min:3|max:100|unique:piutang_agreements,agreement_number,' . $this->agreement_id,
            'lampiran' => 'nullable|string|max:255',
            'perihal' => 'required|string|min:3|max:255',
            'leadCompany' => 'required|string|min:3|max:255',
            'leadName' => 'required|string|min:3|max:100',
            'leadPoss' => 'required|string|min:3|max:100',
            'browCompany' => 'required|string|min:3|max:100',
            'browName' => 'required|string|min:3|max:100',
            'browAddress' => 'nullable|string|max:255',
            'browPoss' => 'nullable|string|max:100',
            'agreeDate' => 'required|date',
            'content' => 'required|string|min:10',
        ];
    }


    protected function messages()
    {
        return [
            'nomorDokument.required' => 'Nomor dokumen wajib diisi.',
            'perihal.required' => 'Perihal wajib diisi.',
            'leadName.required' => 'Nama pimpinan perusahaan wajib diisi.',
            'leadPoss.required' => 'Posisi pimpinan perusahaan wajib diisi.',
            'browName.required' => 'Nama customer wajib diisi.',
            'agreeDate.required' => 'Tanggal perjanjian wajib diisi.',
            'content.required' => 'Isi perjanjian wajib diisi.',
        ];
    }

    public function setPiutang(Piutang $piutang): void
    {
        $this->piutang_id = $piutang->id;

        // Jika perjanjian sudah ada, load datanya untuk diedit
        $this->agreement = $piutang->agreement;
        if ($this->agreement) {
            $this->agreement_id = $this->agreement->id;
            $this->fill([
                'nomorDokument' => $this->agreement->agreement_number,
                'lampiran' => $this->agreement->agreement_lampiran,
                'perihal' => $this->agreement->agreement_perihal,
                'leadCompany' => $this->agreement->leader_company,
                'leadName' => $this->agreement->leader_name,
                'leadPoss' => $this->agreement->leader_position,
                'browCompany' => $this->agreement->borrower_company,
                'browName' => $this->agreement->borrower_name,
                'browAddress' => $this->agreement->borrower_address,
                'browPoss' => $this->agreement->borrower_position,
                'agreeDate' => $this->agreement->agreement_date,
                'content' => $this->agreement->content,
                'generatePdf' => $this->agreement->generated_pdf
            ]);
        } else {
            $this->browName = $piutang->user->name;
            $this->browAddress = $piutang->user->setting->address;
            $this->agreeDate = Carbon::now()->format('Y-m-d');
        }
    }


    public function save()
    {
        $this->validate();

        $piutangAgreement = PiutangAgreement::where('piutang_id', $this->piutang_id)->first();
        if ($piutangAgreement && $piutangAgreement->generated_pdf && Storage::disk('public')->exists($piutangAgreement->generated_pdf)) {
            Storage::disk('public')->delete($piutangAgreement->generated_pdf);
        }
        $path = null;
        if ($this->generatePdf) {
            $path = $this->generatePdf->storeAs('piutangs/mou', $this->generatePdf->hashName(), 'public');
        }

        PiutangAgreement::updateOrCreate(
            ['piutang_id' => $this->piutang_id],
            [
                'agreement_number' => $this->nomorDokument,
                'agreement_lampiran' => $this->lampiran,
                'agreement_perihal' => $this->perihal,
                'leader_company' => $this->leadCompany,
                'leader_name' => $this->leadName,
                'leader_position' => $this->leadPoss,
                'borrower_company' => $this->browCompany,
                'borrower_name' => $this->browName,
                'borrower_address' => $this->browAddress,
                'borrower_position' => $this->browPoss,
                'agreement_date' => $this->agreeDate,
                'content' => $this->content,
                'generated_pdf' => $path ?? $piutangAgreement?->generated_pdf
            ]
        );
    }
}
