<?php

namespace App\Livewire\Company\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoryCreate extends Component
{
    use WithFileUploads;
    public CategoryForm $form;

    public function store()
    {
        try {
            $this->form->store();
            Flux::modal('create-category')->close();
            $this->dispatch('reloadCategories');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.company.categories.category-create');
    }
}
