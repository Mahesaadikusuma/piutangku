<?php

namespace App\Livewire\Company\Categories;

use App\Exports\Categories\CategoryExport;
use App\Models\Category;
use App\Repository\CategoryRepository;
use App\Repository\Interface\CategoryInterface;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Categories')]
class Categories extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';

    protected CategoryInterface $categoryRepo;
    public function boot(CategoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
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

    #[On('reloadCategories')]
    public function reloadCategories()
    {
        return $this->categoryRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage']);
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->dispatch('categoryEdit', $id);
    }

    public function delete($id)
    {
        $this->dispatch('categoryDelete', $id);
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new CategoryExport($this->categoryRepo, $this->search, $this->sortBy),
                'Categories.xlsx'
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
        $categories = $this->categoryRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
        return view('livewire.company.categories.categories', compact('categories'));
    }
}
