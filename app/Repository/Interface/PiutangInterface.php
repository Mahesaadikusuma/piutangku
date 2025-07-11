<?php

namespace App\Repository\Interface;

use App\Models\Piutang;
use Illuminate\Database\Eloquent\Builder;

interface PiutangInterface
{
    public function getFilteredQueryNotProducts(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $status = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder;

    public function paginateFilteredNotProducts(
        $search = null,
        $customerFilter = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    );

    public function allFilteredNotProducts(
        $search,
        $customerFilter,
        $statusFilter,
        $year,
        $month,
        $sortBy
    );

    public function getFilteredQueryProducts(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $status = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder;
    public function paginateFilteredProducts(
        $search = null,
        $customerFilter = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    );
    public function allFilteredProducts(
        $search,
        $customerFilter,
        $statusFilter,
        $year,
        $month,
        $sortBy = 'newest',
    );
    public function generateKodePiutang(): string;
    public function createPiutang(array $data): Piutang;
    public function update(Piutang $piutang, array $data): bool;
    public function agePiutangPerCustomerQuery(?string $search = null);
    public function agePiutangPerCustomerPaginate(int $limit = 25, $search = null);
    public function getPiutangCount();
    public function getPiutangCountByUser();
    public function getTotalPiutang();
    public function getTotalSisaPiutang();
    public function getPiutangTotalByUser();
    public function getPiutangSisaHutangByUser();

    public function getFilteredQueryByUserPiutangs(
        ?string $search = null,
        ?string $status = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder;

    public function paginateFilteredByUserPiutangs(
        $search = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    );
    public function allFilteredByUserPiutangs(
        $search,
        $statusFilter,
        $year,
        $month,
        $sortBy = 'newest',
    );
}
