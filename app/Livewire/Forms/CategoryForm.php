<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use App\Repository\Interface\CategoryInterface;
use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class CategoryForm extends Form
{
    use WithFileUploads;
    public ?Category $category;

    #[Validate()]
    public $name;
    public $thumbnail;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Category name harus di isi.',
            'name.min' => 'Category Minimal characters 3 huruf.',
            'name.max' => 'Category name terlalu panjang maximal 60 huruf.',
            'thumbnail.image' => 'thumbnail Harus di isi image.',
            'thumbnail.mimes' => 'thumbnail Harus di isi type image jpg,jpeg,png,svg.',
            'thumbnail.max' => 'thumbnail maximal file 2Mb.',
        ];
    }

    protected CategoryInterface $categoryRepository;
    public function boot(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
    }

    public function store()
    {
        try {
            $this->validate();
            $thumbnailPath = null;
            if ($this->thumbnail) {
                $thumbnailPath = $this->thumbnail->storeAs('category/thumbnail', $this->thumbnail->hashName(), 'public');
            }
            $data = [
                'name' => $this->name,
                'thumbnail' => $thumbnailPath ?? null
            ];
            $this->categoryRepository->createCategory($data);
            $this->reset();
            // Toaster::success('Category Telah Di Tambahkan');
        } catch (Exception $e) {
            // Toaster::error('Category error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update()
    {
        if (!$this->category) {
            // Toaster::error('Kategori tidak ditemukan.');
            return;
        }

        try {
            $this->validate();

            $data = ['name' => $this->name];
            if ($this->thumbnail) {
                $data['thumbnail'] = $this->thumbnail;
            }
            $this->categoryRepository->updateCategory($data, $this->category);
        } catch (Exception $e) {
            // Toaster::error('Terjadi kesalahan saat mengupdate kategori.');
            throw $e;
        }
    }


    public function destroy()
    {
        $this->categoryRepository->deleteCategory($this->category);
    }
}
