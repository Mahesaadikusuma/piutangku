<?php

namespace App\Exports\Users\Pdf;

use App\Models\User;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class UsersPdfExport implements FromView, ShouldAutoSize
{
    protected UserRepository $userRepo;
    protected $search;
    protected $sortBy;
    protected $filteredCount = 0;

    public function __construct(
        UserRepository $userRepo,
        $search = null,

        $sortBy = 'newest'
    ) {
        $this->userRepo = $userRepo;
        $this->search = $search;
        $this->sortBy = $sortBy;
    }

    public function view(): View
    {
        $now = Carbon::now();
        $users = $this->userRepo
            ->allFilteredUsers(
                $this->search,
                $this->sortBy
            );
        return view('pdf.users.users', [
            'users' => $users,
            'now' => $now
        ]);
    }
}
