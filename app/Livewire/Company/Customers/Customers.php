<?php

namespace App\Livewire\Company\Customers;

use App\Exports\Customers\CustomerExport;
use App\Models\Customer;
use App\Repository\CustomerRepository;
use App\Repository\Interface\CustomerInterface;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Customers')]
class Customers extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';

    protected CustomerInterface $customerRepo;
    public function boot(CustomerInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => [
                'as' => 'q',
            ],
        ];
    }

    #[On('reloadCustomers')]
    public function reloadCategories()
    {
        return $this->customerRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage']);
        $this->resetPage();
    }

    public function delete($id)
    {
        $this->dispatch('customerDelete', $id);
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new CustomerExport($this->customerRepo, $this->search, $this->sortBy),
                'Customers.xlsx'
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
        $customers = $this->customerRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
        return view('livewire.company.customers.customers', compact('customers'));
    }
}
