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
use App\Exports\Piutangs\AgeCustomerExport;
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

    protected function queryString()
    {
        return [
            'status' => ['except' => ''],
            'years' => ['except' => null],
        ];
    }

    public function mount()
    {
        $this->month = array_values(Helpers::getMonths());
        $this->getTotalPiutangPerMonth($this->status, $this->years);
    }

    public function updateChart()
    {
        $this->reset(['month', 'totalPiutangByMonth']);
        $this->getTotalPiutangPerMonth($this->status, $this->years);

        $this->dispatch('filter', [
            'dateTime' => $this->month,
            'orders' => $this->totalPiutangByMonth,
            'colors' => $this->status == 'Failed' ? '#f53333' : ($this->status == 'Success' ? '#039e03' : '#FFA500'),
        ]);
    }


    public function getTotalPiutangPerMonth($status = null, $years = null)
    {
        $raw = Piutang::query()
            ->selectRaw('MONTH(tanggal_transaction) as month, SUM(jumlah_piutang) as total')
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($years, fn($q) => $q->whereYear('tanggal_transaction', $years))
            ->groupByRaw('MONTH(tanggal_transaction)')
            ->orderByRaw('MONTH(tanggal_transaction)')
            ->pluck('total', 'month')
            ->toArray();

        // Set 12 bulan dengan default 0
        $this->totalPiutangByMonth = [];
        foreach (range(1, 12) as $m) {
            $this->totalPiutangByMonth[] = floor($raw[$m] ?? 0);
        }
    }

    public function resetFilter()
    {
        $this->reset(['month', 'totalPiutangByMonth']);
        $this->reset(['status', 'years']);

        // Inisialisasi ulang bulan
        $this->month = array_values(Helpers::getMonths());
        // Ambil ulang data piutang
        $this->getTotalPiutangPerMonth($this->status, $this->years);
        // Dispatch ke chart
        $this->dispatch('filter', [
            'dateTime' => $this->month,
            'orders' => $this->totalPiutangByMonth,
            'colors' => $this->status == 'Failed' ? '#f53333' : ($this->status == 'Success' ? '#039e03' : '#FFA500'),
        ]);
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
        $getYears = Helpers::getYears();

        // bisa dituker untuk melihat bulan
        $this->month = array_values(Helpers::getMonths());
        $this->updateChart();
        return view('livewire.company.dashboard', [
            'ageCustomer' => $ageCustomer,
            'data' => $data,
            'getYears' => $getYears
        ]);
    }
}
