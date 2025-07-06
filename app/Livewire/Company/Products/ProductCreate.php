<?php

namespace App\Livewire\Company\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Repository\Interface\CategoryInterface;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;
    public ProductForm $form;

    protected CategoryInterface $categoryRepo;
    public function boot(CategoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }


    public function store()
    {
        try {
            $this->form->store();
            Flux::modal('create-product')->close();
            $this->dispatch('reloadProducts');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    #[Computed()]
    public function categories()
    {
        return $this->categoryRepo->getAll();
    }

    public function render()
    {
        return view('livewire.company.products.product-create');
    }
}
