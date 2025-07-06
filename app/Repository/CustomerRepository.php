<?php

namespace App\Repository;

use App\Models\Customer;
use App\Repository\Interface\CustomerInterface;
use Illuminate\Support\Facades\Log;
use Masmerise\Toaster\Toaster;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository implements CustomerInterface
{
    public function getAll()
    {
        return Customer::with(['setting', 'user'])->all();
    }

    public function getFilteredQuery($search = null, $sortBy = 'newest')
    {
        return Customer::query()->with(['setting', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('code_customer', 'like', '%' . $search . '%')
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
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


    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }

    public function updateCustomer(array $data, $customer): Customer
    {
        $customer->update($data);
        return $customer;
    }

    public function deletedCustomer($customerId): bool
    {
        return $customerId->delete();
    }
}
