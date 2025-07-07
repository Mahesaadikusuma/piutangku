<?php

namespace App\Livewire\Company;

use Carbon\Carbon;
use App\Models\Piutang;
use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Number;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Service\DashboardService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\piutangs\AgeCustomerExport;
use App\Repository\Interface\PiutangInterface;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    use WithPagination;

    #[Url()]
    public $search = '';

    #[Url()]
    public $limit = 25;

    public $years = null;
    public $status = 'Pending';

    public $totalPiutangByMonth;
    public $month;


    protected DashboardService $dashboardService;
    protected PiutangInterface $piutangInterface;
    public function boot(DashboardService $dashboardService, PiutangInterface $piutangInterface)
    {
        $this->dashboardService = $dashboardService;
        $this->piutangInterface = $piutangInterface;
    }

    public function getTotalPiutangPerMonth(): array
    {
        $raw = Piutang::query()
            ->selectRaw('MONTH(tanggal_transaction) as month, SUM(jumlah_piutang) as total')
            // ->when($this->years, fn($q) => $q->whereYear('tanggal_transaction', $this->years)) // ⬅️ pindah ke atas
            ->when($this->status !== '', fn($q) => $q->where('status_pembayaran', $this->status))
            ->groupByRaw('MONTH(tanggal_transaction)')
            ->orderByRaw('MONTH(tanggal_transaction)')
            ->pluck('total', 'month')
            ->toArray();
        // Isi default semua bulan 0
        $data = [];
        foreach (range(1, 12) as $month) {
            $data[] = $raw[$month] ?? 0;
        }
        return $data;
    }

    public function resetFilter()
    {
        $this->reset(['years']);
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new AgeCustomerExport(
                    $this->piutangInterface,
                    $this->search,
                ),
                'Age Piutangs.xlsx'
            );

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
        $data = $this->dashboardService->DashboardAnalytics();
        $ageCustomer = $this->dashboardService->agePiutangCustomer($this->limit, $this->search);
        // dd($ageCustomer);
        $this->month = array_values(Helpers::getMonths());
        $getYears = Helpers::getYears();
        $this->totalPiutangByMonth = $this->getTotalPiutangPerMonth();
        $this->dispatch('filter', [
            'dateTime' => $this->month,
            'orders' => $this->totalPiutangByMonth,
        ]);

        return view('livewire.company.dashboard', [
            'ageCustomer' => $ageCustomer,
            'data' => $data,
            'getYears' => $getYears
        ]);
    }
}
