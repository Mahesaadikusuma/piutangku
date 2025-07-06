<?php

namespace App\Livewire\Company\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductDelete extends Component
{
    use WithFileUploads;
    public ProductForm $form;

    #[On('productDelete')]
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        $this->form->setProduct($product);
        Flux::modal('delete-product')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-product')->close();
            $this->dispatch('reloadProducts');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.products.product-delete');
    }
}
