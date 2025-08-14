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
use App\Models\PiutangProduct;
use App\Models\Product;
use App\Repository\Interface\PiutangInterface;
use Illuminate\Support\Benchmark;

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
    public $countProductsPiutang;
    public $topProducts;
    public $month;
    public $seriesProducts;
    public $labelsProducts;
    public $statusPiutang;
    public $countPiutang;


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
        $dashboardService = $this->dashboardService->DashboardAnalytics();
        $getPiutangTotals = $dashboardService['getPiutangTotals'];
        $this->countProductsPiutang = $dashboardService['countProductsPiutang'];
        $this->statusPiutang = $getPiutangTotals->pluck('status')->toArray();
        $this->countPiutang = $getPiutangTotals->pluck('total')->toArray();
        $this->topProducts = Product::whereIn('id', array_keys($this->countProductsPiutang))
            ->get(['id', 'name'])
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'total' => $this->countProductsPiutang[$product->id] ?? 0
                ];
            })
            ->sortByDesc('total')
            ->take(15)
            ->values();

        $this->seriesProducts = $this->topProducts->pluck('total')->toArray();
        $this->labelsProducts = $this->topProducts->pluck('name')->toArray();
        $this->dispatch('filter', [
            'dateTime' => $this->month,
            'orders' => $this->totalPiutangByMonth,
            'seriesProducts' => $this->seriesProducts,
            'labelsProducts' => $this->labelsProducts,
            'statusPiutang' => $this->statusPiutang,
            'countPiutang' => $this->countPiutang,
            'colors' => $this->status == 'Failed' ? '#f53333' : ($this->status == 'Success' ? '#039e03' : '#FFA500'),
        ]);
    }

    public function getTotalPiutangPerMonth($status = null, $years = null)
    {
        $raw = $this->piutangInterface->getTotalPiutangPerMonth($status, $years);

        // Set 12 bulan dengan default 0
        $this->totalPiutangByMonth = [];
        foreach (range(1, 12) as $month) {
            $this->totalPiutangByMonth[] = floor($raw[$month] ?? 0);
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
