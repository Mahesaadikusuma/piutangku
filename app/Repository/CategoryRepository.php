<?php

namespace App\Repository;

use App\Data\CategoryData;
use App\Models\Category;
use App\Repository\Interface\CategoryInterface;
use Illuminate\Support\Facades\Storage;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryInterface
{
    public function getAll()
    {
        return Category::select('id', 'name')->get();
    }

    public function getFilteredQuery($search = null, $sortBy = 'newest')
    {
        return Category::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10)
    {
        return $this->getFilteredQuery($search, $sortBy)->paginate($perPage);
    }

    public function allFiltered($search = null, $sortBy = 'newest')
    {
        return $this->getFilteredQuery($search,  $sortBy)->get();
    }

    public function getCategoryDetail($id)
    {
        return Category::find($id);
    }

    public function createCategory(array $data): Category
    {
        $categoryCreate = Category::create($data);
        return $categoryCreate;
    }

    public function updateCategory($data, $category): Category
    {
        if (isset($data['thumbnail'])) {
            if ($category->thumbnail && Storage::disk('public')->exists($category->thumbnail)) {
                Storage::disk('public')->delete($category->thumbnail);
            }

            $data['thumbnail'] = $data['thumbnail']->storeAs('category/thumbnail', $data['thumbnail']->hashName(), 'public');
        }
        $category->update($data);
        return $category;
    }


    public function deleteCategory($categoryId)
    {
        if ($categoryId->thumbnail && Storage::disk('public')->exists($categoryId->thumbnail)) {
            Storage::disk('public')->delete($categoryId->thumbnail);
        }
        return $categoryId->delete();
    }
}
