<?php

namespace App\Livewire\Company\Piutangs;

use App\Exports\Piutangs\PiutangsExport;
use App\Helpers\Helpers;
use App\Models\Piutang;
use App\Repository\Interface\PiutangInterface;
use App\Repository\PiutangRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
#[Title('Piutangs')]
class Piutangs extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $customerFilter = '';
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
            'perPage' => ['except' => '', 'as' => 'page'],
            'years' => ['except' => null, 'as' => 'year'],
            'months' => ['except' => null, 'as' => 'month'],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage', 'customerFilter', 'status', 'years', 'months']);
        $this->resetPage();
    }

    #[On('reloadPiutangs')]
    public function reloadCategories()
    {
        return $this->piutangRepo->paginateFilteredNotProducts(
            $this->search,
            $this->customerFilter,
            $this->status,
            $this->years,
            $this->months,
            $this->sortBy,
            $this->perPage
        );
    }

    #[Computed()]
    public function customers()
    {
        return DB::table('customers')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.id', 'customers.code_customer', 'customers.user_id', 'users.name')
            ->get();
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new PiutangsExport(
                    $this->piutangRepo,
                    $this->search,
                    $this->customerFilter,
                    $this->status,
                    $this->years,
                    $this->months,
                    $this->sortBy,
                ),
                'Piutang.xlsx'
            );

            session()->flash('success', 'Excel exported successfully.');
            return $export;
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'Excel export failed.');
            return back();
        }
    }

    public function downloadPdf()
    {
        $piutang = collect();
        try {
            $now = Carbon::now();
            $piutangs = $this->piutangRepo->paginateFilteredNotProducts(
                $this->search,
                $this->customerFilter,
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

    public function downloadPdfById($id)
    {
        $piutang = Piutang::with(['products', 'user', 'transactions'])->findOrFail($id);

        $now = Carbon::now();

        $pdf = Pdf::loadView('pdf.piutang-product-user', [
            'piutang' => $piutang,
            'now' => $now,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, 'piutang-detail.pdf');
    }

    public function delete($id)
    {
        $this->dispatch('piutangDelete', $id);
    }

    public function render()
    {
        $piutangs = $this->piutangRepo->paginateFilteredNotProducts(
            $this->search,
            $this->customerFilter,
            $this->status,
            $this->years,
            $this->months,
            $this->sortBy,
            $this->perPage
        );
        $getYears = Helpers::getYears();
        $getMonths = Helpers::getMonths();
        return view('livewire.company.piutangs.piutangs', compact('piutangs', 'getYears', 'getMonths'));
    }
}
