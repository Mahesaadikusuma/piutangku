<?php

namespace App\Livewire\Company\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;
    public ProductForm $form;

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
        return Category::select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.company.products.product-create');
    }
}
