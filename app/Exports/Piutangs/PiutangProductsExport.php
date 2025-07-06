<?php

namespace App\Exports\Piutangs;

use App\Models\Piutang;
use App\Repository\PiutangRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class PiutangProductsExport implements FromView, ShouldAutoSize
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
            ->allFilteredProducts(
                $this->search,
                $this->customerFilter,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy
            );
        return view('excel.piutangs.piutang-product', [
            'piutangs' => $piutangs,
        ]);
    }
}
