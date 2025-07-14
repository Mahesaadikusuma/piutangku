<?php

namespace App\Livewire\Customers\Piutangs;

use App\Exports\Piutangs\piutangByUser;
use App\Helpers\Helpers;
use App\Models\Piutang;
use App\Repository\Interface\PiutangInterface;
use App\Repository\PiutangRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Daftar Hutang')]
class PiutangCustomers extends Component
{
    use WithPagination;

    public $user;
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $status = '';
    public $years = null;
    public $months = null;

    protected PiutangInterface $piutangRepo;
    public function boot(PiutangInterface $piutangRepo)
    {
        $this->piutangRepo = $piutangRepo;
    }

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => ['as' => 'q',],
            'customerFilter' => ['except' => '', 'as' => 'customer'],
            'status' => ['except' => ''],
            'years' => ['except' => null, 'as' => 'year'],
            'months' => ['except' => null, 'as' => 'month'],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage', 'status', 'years', 'months']);
        $this->resetPage();
    }

    public function mount()
    {
        $this->user = Auth::User();
    }

    public function downloadPdf()
    {
        $piutang = collect();
        try {
            $now = Carbon::now();
            $piutangs = $this->piutangRepo->paginateFilteredByUserPiutangs(
                $this->search,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy,
                $this->perPage
            );

            $pdf = Pdf::loadView('pdf.piutangs.piutangs', [
                'piutangs' => $piutangs,
                'now' => $now
            ])->setPaper('a4', 'landscape');

            session()->flash('success', 'PDF exported successfully.');
            return response()->streamDownload(function () use ($pdf) {
                echo  $pdf->stream();
            }, 'Piutangs.pdf');
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'PDF export failed.');
            return back();
        }
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new piutangByUser(
                    $this->piutangRepo,
                    $this->search,
                    $this->status,
                    $this->years,
                    $this->months,
                    $this->sortBy,
                ),
                'Piutangs.xlsx'
            );

            session()->flash('success', 'Excel exported successfully.');
            return $export;
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'Excel export failed.');
            return back();
        }
    }

    public function downloadPdfById($id)
    {
        $piutang = Piutang::with(['products', 'user', 'transactions'])
            ->where('user_id', $this->user->id)->find($id);

        $now = Carbon::now();

        $pdf = Pdf::loadView('pdf.piutang-product-user', [
            'piutang' => $piutang,
            'now' => $now,
        ]);


        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, 'piutang-detail.pdf');
    }

    public function render()
    {
        $piutangs = $this->piutangRepo->paginateFilteredByUserPiutangs(
            $this->search,
            $this->status,
            $this->years,
            $this->months,
            $this->sortBy,
            $this->perPage
        );
        $getYears = Helpers::getYears();
        $getMonths = Helpers::getMonths();
        return view('livewire.customers.piutangs.piutang-customers', compact('piutangs', 'getYears', 'getMonths'));
    }
}
