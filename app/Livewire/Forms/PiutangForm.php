<?php

namespace App\Livewire\Forms;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Repository\Interface\PiutangInterface;
use App\Repository\PiutangRepository;
use App\Service\PiutangService;
use Carbon\Carbon;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class PiutangForm extends Form
{
    use WithFileUploads;
    public ?Piutang $piutang;

    #[Validate]
    public $nomorFaktur;
    public $nomorOrder;
    public $userId;
    public $jumlahFaktur;
    public $ppn = 11;
    public $terms = 10;
    public $tanggalTransaction;
    public $tanggalJatuhTempo;
    public $customer;
    public $statusPembayaran;
    public $sisaHutang;
    public $buktiPembayaran;
    public $kodePiutang;
    public $jumlahPpn;
    public $grandTotal;
    public $tanggalKirim;

    protected PiutangInterface $piutangRepository;
    protected PiutangService $piutangService;
    public function boot(PiutangInterface $piutangRepository, PiutangService $piutangService)
    {
        $this->piutangRepository = $piutangRepository;
        $this->piutangService = $piutangService;
    }

    protected function rules()
    {
        $id = $this->piutang?->id ?? null;
        return [
            'nomorFaktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur,' . $id,
            'nomorOrder' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order,' . $id,
            'userId' => 'required|exists:users,id',
            'jumlahFaktur' => 'required|min:1',
            'ppn' => 'required|integer|min:0',
            'terms' => 'required|integer|min:10',
            'tanggalTransaction' => 'required|date',
            'tanggalJatuhTempo' => 'required|date|after_or_equal:awalTempo',
            'tanggalKirim' => 'nullable|date'
        ];
    }


    protected function messages()
    {
        return [
            'nomorFaktur.min' => 'Nomor faktur minimal 3 karakter.',
            'nomorFaktur.max' => 'Nomor faktur maksimal 100 karakter.',
            'nomorFaktur.unique' => 'Nomor faktur sudah digunakan.',
            'nomorOrder.min' => 'Nomor order minimal 3 karakter.',
            'nomorOrder.max' => 'Nomor order maksimal 100 karakter.',
            'nomorOrder.unique' => 'Nomor order sudah digunakan.',
            'userId.required' => 'Customer harus dipilih.',
            'userId.exists' => 'Customer yang dipilih tidak valid.',
            'jumlahFaktur.required' => 'Jumlah faktur harus diisi.',
            'jumlahFaktur.numeric' => 'Jumlah faktur harus berupa angka.',
            'jumlahFaktur.min' => 'Jumlah faktur minimal 1.',
            'ppn.required' => 'PPN harus diisi.',
            'ppn.numeric' => 'PPN harus berupa angka.',
            'ppn.min' => 'PPN tidak boleh negatif.',
            'terms.required' => 'Jangka waktu cicilan harus diisi.',
            'terms.integer' => 'Jangka waktu cicilan harus berupa angka.',
            'terms.min' => 'Jangka waktu cicilan minimal 10 hari.',
            'tanggalTransaction.required' => 'Tanggal transaksi harus diisi.',
            'tanggalTransaction.date' => 'Tanggal transaksi tidak valid.',
            'tanggalJatuhTempo.required' => 'Tanggal jatuh tempo harus diisi.',
            'tanggalJatuhTempo.date' => 'Tanggal jatuh tempo tidak valid.',
            'tanggalJatuhTempo.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal transaksi.',
            'tanggalKirim.date' => 'Tanggal kirim tidak valid.',
        ];
    }


    public function store()
    {
        try {
            $this->validate();
            $data = [
                'user_id' => $this->userId,
                'nomor_faktur' => $this->nomorFaktur,
                'nomor_order' => $this->nomorOrder,
                'jumlah_piutang' => $this->grandTotal,
                'ppn' => $this->ppn,
                'terms' => $this->terms,
                'tanggal_transaction' => $this->tanggalTransaction,
                'tanggal_jatuh_tempo' => $this->tanggalJatuhTempo,
            ];
            $this->piutangRepository->createPiutang($data);
            session()->flash('success', 'Piutang created successfully.');
        } catch (Exception $e) {
            session()->flash('error', 'Piutang creation failed.');
            throw $e;
        }
    }


    public function update()
    {
        try {
            $this->validate();
            if ($this->piutang->status_pembayaran === StatusType::SUCCESS->value) {
                $this->sisaHutang = 0;
            } else {
                $this->sisaHutang = $this->grandTotal;
            }
            $data = [
                'nomor_faktur' => $this->nomorFaktur,
                'nomor_order' => $this->nomorOrder,
                'jumlah_piutang' => $this->grandTotal,
                'ppn' => $this->ppn,
                'terms' => $this->terms,
                'tanggal_transaction' => $this->tanggalTransaction,
                'tanggal_jatuh_tempo' => $this->tanggalJatuhTempo,
                'sisa_piutang' => $this->sisaHutang,
                'status_pembayaran' => $this->statusPembayaran,
                'bukti_pembayaran' => $this->buktiPembayaran ?? null,
                'tanggal_kirim' => $this->tanggalKirim
            ];

            if ($this->buktiPembayaran) {
                $data['bukti_pembayaran'] = $this->buktiPembayaran;
            }

            if ($this->statusPembayaran === StatusType::SUCCESS->value) {
                $data['tanggal_lunas'] = now();
            }

            $this->piutangService->updatePiutang($this->piutang, $data);
            session()->flash('success', 'Piutang updated successfully.');
        } catch (Exception $th) {
            session()->flash('error', 'Piutang update failed.');
            throw $th;
        }
    }

    public function setPiutang(Piutang $piutang)
    {
        $this->piutang = $piutang;
        $this->kodePiutang = $piutang->kode_piutang;
        $this->userId = $piutang->user_id;
        $this->nomorFaktur = $piutang->nomor_faktur;
        $this->nomorOrder = $piutang->nomor_order;
        $this->customer = $piutang->user->name;
        $this->jumlahFaktur = $piutang->jumlah_piutang;
        $this->ppn = $piutang->ppn;
        $this->tanggalTransaction = $piutang->tanggal_transaction;
        $this->tanggalJatuhTempo = $piutang->tanggal_jatuh_tempo;
        $this->sisaHutang = $piutang->sisa_piutang;
        $this->statusPembayaran = $piutang->status_pembayaran;
        $this->buktiPembayaran = $piutang->bukti_pembayaran;
        $this->tanggalKirim = $piutang->tanggal_kirim;

        // Tambahkan ini untuk menghitung ulang jumlahPpn & grandTotal saat edit
        $this->hitungJumlahPpn();
    }

    public function setPiutangCreate()
    {
        $this->tanggalTransaction = Carbon::now()->format('Y-m-d');
        $this->tanggalJatuhTempo = Carbon::now()->addDays($this->terms)->format('Y-m-d');
    }

    public function destroy()
    {
        try {
            $this->piutang->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function hitungJumlahPpn()
    {
        $grandTotal = floatval($this->jumlahFaktur ?? 0);
        $ppn = floatval($this->ppn ?? 0);

        if ($ppn > 0) {
            $hargaDasar = round($grandTotal / (1 + ($ppn / 100)), 2);
            $this->jumlahPpn = $grandTotal - $hargaDasar;
            $this->grandTotal = $grandTotal;
        } else {
            $this->jumlahPpn = 0;
            $this->grandTotal = $grandTotal;
        }
    }
}
