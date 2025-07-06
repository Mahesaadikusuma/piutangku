<?php

namespace App\Repository;

use App\Models\Category;
use App\Models\Product;
use App\Repository\Interface\CategoryInterface;
use App\Repository\Interface\PermissionInterface;
use App\Repository\Interface\ProductInterface;
use Illuminate\Support\Facades\Storage;
use Masmerise\Toaster\Toaster;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductInterface
{
    public function getAll(): Builder
    {
        return Product::query()->with(['category']);
    }

    public function getProductLimit($limit = 10)
    {
        return Product::query()
            ->with(['category'])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getFilteredQuery($search = null, $categoryFilter = null, $sortBy = 'newest')
    {
        return Product::query()
            ->with(['category'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($categoryFilter !== null && $categoryFilter !== '', function ($query) use ($categoryFilter) {
                $query->where('category_id', $categoryFilter);
            })
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());

        // $products = Product::query()->with('category')
        //     ->when($this->search, function ($query) {
        //         $query->where('name', 'like', '%' . $this->search . '%');
        //     })
        //     ->when(
        //         $this->categoryFilter !== '',
        //         fn($q) => $q->where('category_id', $this->categoryFilter)
        //     )
        //     ->when($this->sortBy === 'latest', fn($q) => $q->oldest())
        //     ->when($this->sortBy === 'newest', fn($q) => $q->latest());

        // $products = $products->paginate($this->perPage);
    }

    public function paginateFiltered($search = null, $categoryFilter = null, $sortBy = 'newest', $perPage = 10)
    {
        return $this->getFilteredQuery($search, $categoryFilter, $sortBy)->paginate($perPage);
    }

    public function allFiltered($search = null, $categoryFilter = null, $sortBy = 'newest')
    {
        return $this->getFilteredQuery($search, $categoryFilter, $sortBy)->get();
    }


    public function getProduct($id) {}

    public function getOtherProducts($slug)
    {
        return Product::with(['category'])->where('slug', "!=", $slug)->take(5)->get();
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct($data, $product): Product
    {
        if (isset($data['thumbnail'])) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $data['thumbnail']->storeAs('product/image', $data['thumbnail']->hashName(), 'public');
        }

        // Perbarui produk
        $product->update($data);

        return $product;
    }

    public function deleteProduct($productId)
    {
        if ($productId->thumbnail && Storage::disk('public')->exists($productId->thumbnail)) {
            Storage::disk('public')->delete($productId->thumbnail);
        }

        return $productId->delete();
    }
}
