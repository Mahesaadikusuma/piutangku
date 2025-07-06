<?php

namespace App\Livewire\Company;

use App\Models\Piutang;
use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Number;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Service\DashboardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    use WithPagination;

    #[Url()]
    public $search = '';

    #[Url()]
    public $limit = 20;

    public $years = null;
    public $status = 'Pending';

    public $totalPiutangByMonth;
    public $month;


    protected DashboardService $dashboardService;
    public function boot(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
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

    public function render()
    {
        $data = $this->dashboardService->DashboardAnalytics();
        $ageCustomer = $this->dashboardService->agePiutangCustomer($this->limit, $this->search);

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
