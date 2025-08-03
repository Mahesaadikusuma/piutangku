<?php

namespace App\Livewire\Company\Products;

use App\Exports\Products\ProductsExport;
use App\Models\Product;
use App\Repository\Interface\ProductInterface;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Products')]
class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $categoryFilter = '';

    protected ProductInterface $productRepo;
    public function boot(ProductInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => ['as' => 'q',],
            'categoryFilter' => ['except' => ''],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage', 'categoryFilter']);
        $this->resetPage();
    }

    #[On('reloadProducts')]
    public function reloadCategories()
    {
        return $this->productRepo->paginateFiltered(
            $this->search,
            $this->categoryFilter,
            $this->sortBy,
            $this->perPage
        );
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new ProductsExport($this->productRepo, $this->search, $this->categoryFilter, $this->sortBy),
                'products.xlsx'
            );
            session()->flash('success', 'Excel exported successfully.');
            return $export;
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'Excel export failed.');
            return back();
        }
    }


    public function edit($id)
    {
        $this->dispatch('productEdit', $id);
    }

    public function delete($id)
    {
        $this->dispatch('productDelete', $id);
    }

    #[Computed()]
    public function categories()
    {
        return DB::table('categories')->select('id', 'name')->get();
    }

    public function render()
    {
        $products = $this->productRepo->paginateFiltered(
            $this->search,
            $this->categoryFilter,
            $this->sortBy,
            $this->perPage
        );
        return view('livewire.company.products.products', [
            'products' => $products
        ]);
    }
}
