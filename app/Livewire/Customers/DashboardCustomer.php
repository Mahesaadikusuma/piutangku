<?php

namespace App\Livewire\Customers;

use App\Helpers\Helpers;
use App\Repository\Interface\PiutangInterface;
use App\Service\DashboardService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class DashboardCustomer extends Component
{
    public $status = 'Pending';
    public $years = null;
    public $month;
    public $totalPiutangByMonth;
    public $sisaPiutangByMonth = [];
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
        $dashboardService = $this->dashboardService->DashboardAnalyticsCustomer();
        $getPiutangTotalsByUser = $dashboardService['getPiutangTotalsByUser'];

        $this->statusPiutang = $getPiutangTotalsByUser->pluck('status')->toArray();
        $this->countPiutang = $getPiutangTotalsByUser->pluck('total')->toArray();

        $this->dispatch('filter', [
            'dateTime' => $this->month,
            'totalPiutang' => $this->totalPiutangByMonth,
            'sisaPiutang' => $this->sisaPiutangByMonth,
            'statusPiutang' => $this->statusPiutang,
            'countPiutang' => $this->countPiutang,
            'colors' => $this->status == 'Failed' ? '#f53333' : ($this->status == 'Success' ? '#039e03' : '#FFA500'),
        ]);
    }

    public function getTotalPiutangPerMonth($status = null, $years = null)
    {
        $totalPiutang = $this->piutangInterface->getTotalJumlahPiutangPerMonthByUser($status, $years);
        $sisaPiutang =  $this->piutangInterface->getTotalSisaPiutangPerMonthByUser($status, $years);
        // Set 12 bulan dengan default 0
        $this->totalPiutangByMonth = [];
        $this->sisaPiutangByMonth = [];
        foreach (range(1, 12) as $month) {
            $this->totalPiutangByMonth[] = floor($totalPiutang[$month] ?? 0);
            $this->sisaPiutangByMonth[] = floor($sisaPiutang[$month] ?? 0);
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

    public function render()
    {
        $data = $this->dashboardService->DashboardAnalyticsCustomer();
        $getYears = Helpers::getYears();
        $this->updateChart();
        return view('livewire.customers.dashboard-customer', [
            'data' => $data,
            'getYears' => $getYears
        ]);
    }
}
