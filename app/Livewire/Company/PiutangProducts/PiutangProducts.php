<?php

namespace App\Livewire\Company\PiutangProducts;

use App\Exports\Piutangs\PiutangProductsExport;
use App\Helpers\Helpers;
use App\Models\Piutang;
use App\Models\User;
use App\Repository\Interface\PiutangInterface;
use App\Repository\PiutangRepository;
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
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Piutang Product')]
class PiutangProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $customerFilter = '';
    public $status = '';
    public $years = null;
    public $months = null;

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
        $this->reset(['search', 'sortBy', 'perPage', 'customerFilter', 'status', 'years', 'months']);
        $this->resetPage();
    }

    protected PiutangInterface $piutangRepo;
    public function boot(PiutangInterface $piutangRepo)
    {
        $this->piutangRepo = $piutangRepo;
    }

    #[On('reloadPiutangs')]
    public function reloadCategories()
    {
        return $this->piutangRepo->paginateFilteredProducts(
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
                new PiutangProductsExport(
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
            $piutangs = $this->piutangRepo->paginateFilteredProducts(
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
            }, 'piutang-products.pdf');
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'PDF export failed.');
            return back();
        }
    }

    public function downloadPdfById($id)
    {
        $piutang = Piutang::with(['products', 'user', 'transactions'])->find($id);
        $now = Carbon::now();
        $users = User::limit(100)->get();
        $pdf = Pdf::loadView('pdf.piutang-product-user', [
            'piutang' => $piutang,
            'now' => $now,
            'users' => $users
        ]);


        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, 'piutang-product-user.pdf');
    }

    public function delete($id)
    {
        $this->dispatch('piutangDelete', $id);
    }

    public function render()
    {
        $piutangs = $this->piutangRepo->paginateFilteredProducts(
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
        return view('livewire.company.piutang-products.piutang-products', compact('piutangs', 'getYears', 'getMonths'));
    }
}
