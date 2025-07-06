<?php

namespace App\Livewire\Company\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads;
    public ProductForm $form;
    public Product $product;

    #[On('productEdit')]
    public function editProduct($id)
    {
        $this->product = Product::find($id);
        $this->form->setProduct($this->product);
        Flux::modal('edit-product')->show();
    }

    public function update()
    {
        try {
            $this->form->update();
            Flux::modal('edit-product')->close();
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
        return view('livewire.company.products.product-edit');
    }
}
