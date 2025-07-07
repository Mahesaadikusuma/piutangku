<?php

namespace App\Exports\Piutangs;

use App\Repository\PiutangRepository;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PiutangsExport implements FromView, ShouldAutoSize
{
    protected PiutangRepository $piutangRepo;
    protected $search;
    protected $customerFilter;
    protected $status;
    protected $years;
    protected $months;
    protected $sortBy;
    protected $filteredCount = 0;


    public function __construct(
        PiutangRepository $piutangRepo,
        $search = null,
        $customerFilter = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest'
    ) {
        $this->piutangRepo = $piutangRepo;
        $this->search = $search;
        $this->customerFilter = $customerFilter;
        $this->status = $status;
        $this->years = $years;
        $this->months = $months;
        $this->sortBy = $sortBy;
    }

    public function view(): View
    {
        $piutangs = $this->piutangRepo
            ->allFilteredNotProducts(
                $this->search,
                $this->customerFilter,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy
            );
        return view('excel.piutangs.piutangs', [
            'piutangs' => $piutangs,
        ]);
    }

    // public function query()
    // {
    //     $query = $this->piutangRepo
    //         ->getFilteredQueryNotProducts($this->search, $this->customerFilter, $this->status, $this->years, $this->months, $this->sortBy)
    //         ->select('id', 'user_id', 'kode_piutang', 'nomor_faktur', 'nomor_order', 'terms', 'tanggal_transaction', 'tanggal_jatuh_tempo', 'jumlah_piutang', 'sisa_piutang', 'status_pembayaran', 'ppn', 'tanggal_lunas');

    //     // Simpan jumlah hasil query untuk styling nanti
    //     $this->filteredCount = $query->count();

    //     return $query;
    // }

    // public function headings(): array
    // {
    //     return ['#', 'UserName', 'Kode Piutang', 'Nomor Faktur', 'Nomor Order', 'Jangka Waktu', 'Tanggal Transaction', 'Tanggal Jatuh Tempo', 'Jumlah Piutang', 'Sisa Piutang', 'Status', 'PPN', 'Tanggal Lunas'];
    // }

    // public function map($piutang): array
    // {
    //     static $rowNumber = 1;
    //     return [
    //         $rowNumber++,
    //         optional($piutang->user)->name ?? '-',
    //         $piutang->kode_piutang ?? '-',
    //         $piutang->nomor_faktur ?? '-',
    //         $piutang->nomor_order ?? '-',
    //         $piutang->terms ?? '-',
    //         $piutang->tanggal_transaction ?? '-',
    //         $piutang->tanggal_jatuh_tempo ?? '-',
    //         $piutang->jumlah_piutang ?? '-',
    //         $piutang->sisa_piutang ?? '-',
    //         $piutang->status_pembayaran ?? '-',
    //         $piutang->ppn ?? '-',
    //         $piutang->tanggal_lunas ?? '-',
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function (AfterSheet $event) {
    //             $totalRows = $this->filteredCount + 1;
    //             $cellRange = 'A1:M' . $totalRows;

    //             // Styling header
    //             $event->sheet->getStyle('A1:M1')->applyFromArray([
    //                 'font' => [
    //                     'bold' => true,
    //                 ],
    //                 'fill' => [
    //                     'fillType' => Fill::FILL_SOLID,
    //                     'startColor' => [
    //                         'rgb' => 'FFFF00', // Warna kuning
    //                     ],
    //                 ],
    //                 'borders' => [
    //                     'allBorders' => [
    //                         'borderStyle' => Border::BORDER_THIN,
    //                         'color' => ['argb' => '000000'],
    //                     ],
    //                 ],
    //                 'alignment' => [
    //                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 ],
    //             ]);

    //             // Styling seluruh data
    //             $event->sheet->getStyle($cellRange)->applyFromArray([
    //                 'borders' => [
    //                     'allBorders' => [
    //                         'borderStyle' => Border::BORDER_THIN,
    //                         'color' => ['argb' => '000000'],
    //                     ],
    //                 ],
    //                 'alignment' => [
    //                     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 ],
    //             ]);
    //         }
    //     ];
    // }
}
