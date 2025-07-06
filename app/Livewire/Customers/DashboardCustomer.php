<?php

namespace App\Livewire\Customers;

use App\Service\DashboardService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class DashboardCustomer extends Component
{
    protected DashboardService $dashboardService;
    public function boot(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function render()
    {
        $data = $this->dashboardService->DashboardAnalyticsCustomer();
        return view('livewire.customers.dashboard-customer', [
            'data' => $data
        ]);
    }
}
