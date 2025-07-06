<?php

namespace App\Exports\Users;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return User::with(['customer:id,code_customer,user_id', 'setting'])->select('id', 'name', 'email', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['#', 'kode customer', 'UserName', 'FullName', 'Email', 'PhoneNumber', 'address', 'Provinsi', 'Kabupaten / Kota', 'Kecamatan', 'Kelurahan', 'Created At'];
    }

    public function map($user): array
    {
        static $rowNumber = 1;
        return [
            $rowNumber++,
            $user->customer->code_customer ?? 'Belum ada Code customer',
            $user->name,
            $user->setting->full_name ?? 'Belum ada Nama Lengkap',
            $user->email,
            $user->setting->phone_number ?? 'Belum ada Nomor Telepon',
            $user->setting->address ?? 'Belum ada Alamat',
            $user->setting->province->name ?? 'Belum ada Provinsi',
            $user->setting->regency->name ?? 'Belum ada Kabupaten / Kota',
            $user->setting->district->name ?? 'Belum ada Kecamatan',
            $user->setting->village->name ?? 'Belum ada Kelurahan',
            $user->created_at
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Ambil jumlah baris
                $rowCount = User::count() + 1; // +1 untuk header

                // Range area seluruh tabel
                $cellRange = 'A1:L' . $rowCount;

                // Styling header
                $event->sheet->getStyle('A1:L1')->applyFromArray([
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
