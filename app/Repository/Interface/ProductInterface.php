<?php

namespace App\Repository\Interface;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

interface ProductInterface
{
    public function getAll(): Builder;
    public function getProductLimit($limit = 10);
    public function getFilteredQuery($search = null, $categoryFilter = null, $sortBy = 'newest');
    public function paginateFiltered($search = null, $categoryFilter = null, $sortBy = 'newest', $perPage = 10);
    public function allFiltered($search = null, $categoryFilter = null, $sortBy = 'newest');
    public function getProduct($id);
    public function getOtherProducts($slug);
    public function createProduct(array $data): Product;
    public function updateProduct(array $data, $product): Product;
    public function deleteProduct($productId);
}
