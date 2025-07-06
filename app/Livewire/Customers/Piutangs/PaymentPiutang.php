<?php

namespace App\Livewire\Customers\Piutangs;

use App\Models\Piutang;
use App\Service\MidtransService;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Piutang Payment')]
class PaymentPiutang extends Component
{
    public Piutang $piutang;
    public $fullName;
    public $email;
    public $phoneNumber;
    public $kode;
    public $province;
    public $regency;
    public $district;
    public $village;
    public $address;
    public $jumlahPiutang;
    public $sisaHutang;
    public $term;
    public $tanggalTransaction;
    public $tanggalJatuhTempo;

    protected MidtransService $midtransService;
    public function boot(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function mount(Piutang $piutang)
    {
        $this->piutang = $piutang;
        $this->fullName = $piutang->user->setting->full_name;
        $this->email = $piutang->user->email;
        $this->phoneNumber = $piutang->user->setting->phone_number;
        $this->kode = $piutang->kode_piutang;
        $this->province = $piutang->user->setting->province()->first()->name;
        $this->regency = $piutang->user->setting->regency()->first()->name;
        $this->district = $piutang->user->setting->district()->first()->name;
        $this->village = $piutang->user->setting->village()->first()->name;
        $this->address = $piutang->user->setting->address;
        $this->jumlahPiutang = number_format($piutang->jumlah_piutang);
        $this->sisaHutang = number_format($piutang->sisa_piutang);
        $this->term = $piutang->terms;
        $this->tanggalTransaction = Carbon::parse($piutang->tanggal_transaction)->translatedFormat('d F Y');
        $this->tanggalJatuhTempo = Carbon::parse($piutang->tanggal_jatuh_tempo)->translatedFormat('d F Y');
    }

    public function paymentPiutang()
    {
        try {
            $this->midtransService->processPaymentPiutang($this->piutang);
        } catch (Exception $e) {
            session()->flash('error', 'Payment failed.' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.customers.piutangs.payment-piutang');
    }
}
