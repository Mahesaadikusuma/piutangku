<?php

namespace App\Exports\Customers;

use App\Models\Customer;
use App\Repository\CustomerRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CustomerExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $search;
    protected $sortBy;
    protected CustomerRepository $customerRepo;
    protected $filteredCount = 0;

    public function __construct(
        CustomerRepository $customerRepo,
        $search = null,
        $sortBy = 'newest'
    ) {
        $this->customerRepo = $customerRepo;
        $this->search = $search;
        $this->sortBy = $sortBy;
    }

    public function query()
    {
        $query = $this->customerRepo
            ->getFilteredQuery($this->search, $this->sortBy)
            ->select('id', 'code_customer', 'setting_id', 'user_id');
        $this->filteredCount = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return ['#', 'kode Customer', 'userName', 'Full Name', 'Email'];
    }

    public function map($customer): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $customer->code_customer ?? '-',
            optional($customer->user)->name ?? '-',
            optional($customer->setting)->full_name ?? '-',
            optional($customer->user)->email ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRows = $this->filteredCount + 1;
                $cellRange = 'A1:E' . $totalRows;

                // Styling header
                $event->sheet->getStyle('A1:E1')->applyFromArray([
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
