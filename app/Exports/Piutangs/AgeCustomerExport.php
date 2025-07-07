<?php

namespace App\Exports\piutangs;

use App\Repository\PiutangRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AgeCustomerExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected PiutangRepository $piutangRepo;
    protected $search;
    protected $filteredCount = 0;

    public function __construct(
        PiutangRepository $piutangRepo,
        $search = null,
    ) {
        $this->piutangRepo = $piutangRepo;
        $this->search = $search;
    }

    public function query()
    {
        $query = $this->piutangRepo
            ->agePiutangPerCustomerQuery($this->search)
            ->groupBy('users.id', 'users.name', 'customers.uuid', 'customers.code_customer')
            ->orderBy('users.name'); // ✅ Wajib ada!

        // Simpan jumlah hasil query untuk styling nanti
        $this->filteredCount = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return ['#', 'Kode Customer', 'Customer Name', '0 - 30 Hari', '31 - 60 Hari', '61 - 90 Hari', '90+ Hari', 'Total Piutang', 'Sisa Piutang'];
    }

    public function map($piutang): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $piutang->code_customer ?? '-',
            $piutang->user_name ?? '-',
            $piutang->age_0_30 ?? '-',
            $piutang->age_31_60 ?? '-',
            $piutang->age_61_90 ?? '-',
            $piutang->age_90_plus ?? '-',
            $piutang->total_piutang ?? '-',
            $piutang->sisa_piutang ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRows = $this->filteredCount;
                $cellRange = 'A1:I' . $totalRows;

                // Styling header
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FFFF00', // Warna kuning
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Styling seluruh data
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            }
        ];
    }
}
