<?php

namespace App\Livewire\Company\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryDelete extends Component
{
    public CategoryForm $form;

    #[On('categoryDelete')]
    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $this->form->setCategory($category);
        Flux::modal('delete-category')->show();
    }

    public function delete()
    {
        try {
            $this->form->destroy();
            Flux::modal('delete-category')->close();
            $this->dispatch('reloadCategories');
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function render()
    {
        return view('livewire.company.categories.category-delete');
    }
}
