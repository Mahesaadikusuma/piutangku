<?php

namespace App\Repository\Interface;

use App\Models\Customer;

interface CustomerInterface
{
    public function getAll();
    public function getFilteredQuery($search = null, $sortBy = 'newest');
    public function paginateFiltered($search = null, $sortBy = 'newest', $perPage = 10);
    public function allFiltered($search = null, $sortBy = 'newest');
    public function createCustomer(array $data): Customer;
    public function updateCustomer(array $data, $customer): Customer;
    public function deletedCustomer($customerId): bool;
}
