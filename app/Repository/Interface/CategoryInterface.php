<?php

namespace App\Repository\Interface;

use App\Data\CategoryData;

interface CategoryInterface
{
    public function getAll();
    public function getFilteredQuery($search = null, $sortBy = 'newest');
    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10);
    public function allFiltered($search = null, $sortBy = 'newest');
    public function getCategoryDetail($id);
    public function createCategory(array $data);
    public function updateCategory($data, $Category);
    public function deleteCategory($categoryId);
}
