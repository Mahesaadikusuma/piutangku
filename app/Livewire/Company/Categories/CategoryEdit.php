<?php

namespace App\Livewire\Company\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryEdit extends Component
{
    use WithFileUploads;
    public CategoryForm $form;

    #[On('categoryEdit')]
    public function editCategory($id)
    {
        $category = Category::find($id);
        $this->form->setCategory($category);
        Flux::modal('edit-category')->show();
    }

    public function update()
    {
        try {
            $this->form->update();
            Flux::modal('edit-category')->close();
            $this->dispatch('reloadCategories');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.categories.category-edit');
    }
}
