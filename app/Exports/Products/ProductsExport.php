<?php

namespace App\Exports\Products;

use App\Models\Product;
use App\Repository\ProductRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductsExport implements FromQuery, WithHeadings, ShouldAutoSize, WithMapping, WithEvents
{
    protected $search;
    protected $categoryFilter;
    protected $sortBy;
    protected ProductRepository $productRepo;
    protected $filteredCount = 0;

    public function __construct(
        ProductRepository $productRepo,
        $search = null,
        $categoryFilter = null,
        $sortBy = 'newest'
    ) {
        $this->productRepo = $productRepo;
        $this->search = $search;
        $this->categoryFilter = $categoryFilter;
        $this->sortBy = $sortBy;
    }

    public function query()
    {
        $query = $this->productRepo
            ->getFilteredQuery($this->search, $this->categoryFilter, $this->sortBy)
            ->select('id', 'name', 'category_id', 'stock', 'price');

        // Simpan jumlah hasil query untuk styling nanti
        $this->filteredCount = $query->count();

        return $query;
    }

    public function headings(): array
    {
        return ['#', 'Name', 'Category', 'stock', 'price'];
    }

    public function map($product): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $product->name ?? '-',
            optional($product->category)->name ?? '-',
            $product->stock ?? '-',
            $product->price ?? '-',
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
