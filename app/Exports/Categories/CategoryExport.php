<?php

namespace App\Exports\Categories;

use App\Models\Category;
use App\Repository\CategoryRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CategoryExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $search;
    protected $sortBy;
    protected CategoryRepository $categoryRepo;
    protected $filteredCount = 0;

    public function __construct(
        CategoryRepository $categoryRepo,
        $search = null,
        $sortBy = 'newest'
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->search = $search;
        $this->sortBy = $sortBy;
    }

    public function query()
    {
        $query = $this->categoryRepo
            ->getFilteredQuery($this->search, $this->sortBy)
            ->select('id', 'name');
        $this->filteredCount = $query->count();
        return $query;
    }

    public function headings(): array
    {
        return ['#', 'Name'];
    }

    public function map($category): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $category->name ?? '-',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRows = $this->filteredCount + 1;
                $cellRange = 'A1:B' . $totalRows;

                // Styling header
                $event->sheet->getStyle('A1:B1')->applyFromArray([
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
