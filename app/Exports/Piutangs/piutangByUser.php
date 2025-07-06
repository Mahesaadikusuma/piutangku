<?php

namespace App\Exports\Piutangs;

use App\Repository\PiutangRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class piutangByUser implements FromView, ShouldAutoSize
{
    protected PiutangRepository $piutangRepo;
    protected $search;
    protected $status;
    protected $years;
    protected $months;
    protected $sortBy;
    protected $filteredCount = 0;


    public function __construct(
        PiutangRepository $piutangRepo,
        $search = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest'
    ) {
        $this->piutangRepo = $piutangRepo;
        $this->search = $search;
        $this->status = $status;
        $this->years = $years;
        $this->months = $months;
        $this->sortBy = $sortBy;
    }

    public function view(): View
    {
        $piutangs = $this->piutangRepo
            ->allFilteredByUserPiutangs(
                $this->search,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy
            );
        $user = Auth::user();
        return view('excel.piutangs.piutangByUser', [
            'piutangs' => $piutangs,
            'user' => $user
        ]);
    }
}
