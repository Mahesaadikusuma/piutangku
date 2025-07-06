<?php

namespace App\Repository\Interface;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

interface TransactionInterface
{
    public function findTransactionById($id);
    public function getFilteredQueryTransactions(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $status = null,
        ?int $years = null,
        ?int $months = null,
        string $sortBy = 'newest'
    ): Builder;

    public function paginateFilteredTransactions(
        $search = null,
        $customerFilter = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest',
        $perPage = 10
    );

    public function allFilteredTransactions(
        $search,
        $customerFilter,
        $statusFilter,
        $years,
        $months,
        $sortBy
    );

    public function createTransaction(array $data): Transaction;
    public function generateKodeTransaction(): string;
    public function getCountTransactions();
    public function getCountTransactionsByUser();
    public function getFilteredQueryByUserTransactions(
        ?string $search = null,
        ?string $status = null,
        ?int $years = null,
        ?int $months = null,
        string $sortBy = 'newest'
    ): Builder;

    public function paginateFilteredByUserTransactions(
        $search = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest',
        $perPage = 10
    );

    public function allFilteredByUserTransactions(
        $search,
        $statusFilter,
        $years,
        $months,
        $sortBy
    );
}
