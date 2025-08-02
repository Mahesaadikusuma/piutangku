<?php

namespace App\Livewire\Company\Users;

use App\Exports\Users\Pdf\UsersPdfExport;
use App\Exports\Users\UsersExport;
use App\Models\User;
use App\Repository\Interface\UserInterface;
use App\Repository\UserRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Users')]
class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'perPage' => ['except' => 10, 'as' => 'per_page'],
            'search' => ['as' => 'q',],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage']);
        $this->resetPage();
    }

    protected UserInterface $userRepo;
    public function boot(UserInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    #[On('reloadUsers')]
    public function reloadRoles()
    {
        return $this->userRepo->paginateFilteredUsers($this->search, $this->sortBy, $this->perPage);
    }

    public function edit($id)
    {
        $this->dispatch('userEdit', $id);
    }

    public function downloadPdf()
    {
        $users = collect();
        try {
            $now = Carbon::now();
            // $users = $this->userRepo->allFilteredUsers();
            $users = $this->userRepo->paginateFilteredUsers($this->search, $this->sortBy, $this->perPage);

            $pdf = Pdf::loadView('pdf.users.users', [
                'users' => $users,
                'now' => $now
            ]);

            session()->flash('success', 'PDF exported successfully.');
            return response()->streamDownload(function () use ($pdf) {
                echo  $pdf->stream();
            }, 'users.pdf');
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'PDF export failed.');
            return back();
        }
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(new UsersExport, 'Users.xlsx');
            session()->flash('success', 'Excel exported successfully.');
            return $export;
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'Excel export failed.');
            return back();
        }
    }

    public function render()
    {
        $users = $this->userRepo->paginateFilteredUsers($this->search, $this->sortBy, $this->perPage);
        return view('livewire.company.users.users', compact('users'));
    }
}
