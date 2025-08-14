<?php

namespace App\Repository;

use App\Enums\StatusType;
use App\Models\Piutang;
use App\Repository\Interface\PiutangInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class PiutangRepository implements PiutangInterface
{
    public function getFilteredQueryNotProducts(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $status = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder {
        return Piutang::query()
            ->doesntHave('products')
            ->with(['user', 'agreement', 'paymentPiutangs'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_piutang', 'like', '%' . $search . '%')
                        ->orWhere('nomor_faktur', 'like', '%' . $search . '%')
                        ->orWhere('nomor_order', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($customerFilter !== '', fn($q) => $q->where('user_id', $customerFilter))
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($year, fn($q) => $q->whereYear('tanggal_transaction', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_transaction', $month))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredNotProducts(
        $search = null,
        $customerFilter = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryNotProducts(
            $search,
            $customerFilter,
            $status,
            $year,
            $month,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredNotProducts(
        $search,
        $customerFilter,
        $statusFilter,
        $year,
        $month,
        $sortBy
    ) {
        return $this->getFilteredQueryNotProducts(
            $search,
            $customerFilter,
            $statusFilter,
            $year,
            $month,
            $sortBy
        )->get();
    }


    public function getFilteredQueryProducts(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $productFilter = null,
        ?string $status = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder {
        return Piutang::query()
            ->has('products')
            ->with(['user', 'agreement', 'paymentPiutangs', 'products'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_piutang', 'like', '%' . $search . '%')
                        ->orWhere('nomor_faktur', 'like', '%' . $search . '%')
                        ->orWhere('nomor_order', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($customerFilter !== '', fn($q) => $q->where('user_id', $customerFilter))
            ->when($productFilter !== '', function ($q) use ($productFilter) {
                $q->whereHas('products', function ($query) use ($productFilter) {
                    $query->where('piutang_products.product_id', $productFilter);
                });
            })
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($year, fn($q) => $q->whereYear('tanggal_transaction', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_transaction', $month))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredProducts(
        $search = null,
        $customerFilter = null,
        $productFilter = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryProducts(
            $search,
            $customerFilter,
            $productFilter,
            $status,
            $year,
            $month,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredProducts(
        $search,
        $customerFilter,
        $productFilter,
        $statusFilter,
        $year,
        $month,
        $sortBy = 'newest',
    ) {
        return $this->getFilteredQueryProducts(
            $search,
            $customerFilter,
            $productFilter,
            $statusFilter,
            $year,
            $month,
            $sortBy
        )->get();
    }

    public function generateKodePiutang(): string
    {
        $currentDate = Carbon::now()->format('Ym');
        $lastKodePiutang = Piutang::where('kode_piutang', 'LIKE', "$currentDate%")
            ->orderBy('kode_piutang', 'desc')
            ->first();

        $newNumber = $lastKodePiutang
            ? str_pad((int) substr($lastKodePiutang->kode_piutang, -4) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $kodeUnikPiutang = "$currentDate$newNumber"; // Format akhir: 2025030001

        return $kodeUnikPiutang;
    }

    public function createPiutang(array $data): Piutang
    {
        return Piutang::create([
            'user_id' => $data['user_id'],
            'kode_piutang' => $data['kode_piutang'] ?? $this->generateKodePiutang(),
            'nomor_faktur' => $data['nomor_faktur'],
            'nomor_order' => $data['nomor_order'],
            'terms' => $data['terms'],
            'tanggal_transaction' => $data['tanggal_transaction'],
            'tanggal_jatuh_tempo' => $data['tanggal_jatuh_tempo'],
            'jumlah_piutang' => $data['jumlah_piutang'],
            'sisa_piutang' => $data['sisa_piutang'] ?? ($data['jumlah_piutang'] ?? 0),
            'status_pembayaran' => StatusType::PENDING->value,
            'ppn' => $data['ppn'],
            'tanggal_lunas' => $data['tanggal_lunas'] ?? null,
            'bukti_pembayaran' => $data['bukti_pembayaran'] ?? null,
        ]);
    }

    public function update(Piutang $piutang, array $data): bool
    {
        return $piutang->update($data);
    }

    public function agePiutangPerCustomerQuery(?string $search = null)
    {
        $query = DB::query()
            ->from('piutangs')
            ->join('users', 'piutangs.user_id', '=', 'users.id')
            ->join('customers', 'customers.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'customers.uuid as customer_uuid',
                'customers.code_customer as code_customer',
                DB::raw('FORMAT(SUM(jumlah_piutang), 0) as total_piutang'),
                DB::raw('FORMAT(SUM(sisa_piutang), 0) as sisa_piutang'),
                DB::raw('FORMAT(SUM(CASE 
                WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) <= 0 AND DATEDIFF(NOW(), tanggal_jatuh_tempo) >= -30
                THEN jumlah_piutang ELSE 0 END), 0) as age_0_30'),
                DB::raw('FORMAT(SUM(CASE 
                WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) BETWEEN -60 AND -31
                THEN jumlah_piutang ELSE 0 END), 0) as age_31_60'),
                DB::raw('FORMAT(SUM(CASE 
                WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) BETWEEN -90 AND -61
                THEN jumlah_piutang ELSE 0 END), 0) as age_61_90'),
                DB::raw('FORMAT(SUM(CASE 
                WHEN DATEDIFF(NOW(), tanggal_jatuh_tempo) < -90 OR DATEDIFF(NOW(), tanggal_jatuh_tempo) > 0
                THEN jumlah_piutang ELSE 0 END), 0) as age_90_plus'),
            );

        if ($search) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }
        return $query;
    }

    public function agePiutangPerCustomerPaginate(int $limit = 25, $search = null)
    {
        return $this->agePiutangPerCustomerQuery($search)->groupBy('users.id', 'users.name', 'customers.uuid', 'customers.code_customer')->paginate($limit);
    }

    public function getPiutangProductCounts()
    {
        $productCounts = DB::table('piutang_products')
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->groupBy('product_id')
            ->pluck('total', 'product_id')
            ->toArray();
        return $productCounts;
    }

    public function getPiutangTotals()
    {
        return DB::table('piutangs')
            ->select('status_pembayaran as status', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pembayaran')
            ->get();
    }
    public function getPiutangTotalsByUser()
    {
        return DB::table('piutangs')
            ->where('user_id', Auth::user()->id)
            ->select('status_pembayaran as status', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pembayaran')
            ->get();
    }


    public function getPiutangCount()
    {
        $piutang = Piutang::count();
        $precision = $piutang >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }

    public function getPiutangCountByUser()
    {
        $piutang = Piutang::where('user_id', Auth::user()->id)->count();
        $precision = $piutang >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }

    public function getTotalPiutang()
    {
        $piutang = Piutang::sum('jumlah_piutang');
        // dd($piutang);
        $precision = $piutang >= 10000000 ? 3 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }
    public function getTotalSisaPiutang()
    {
        $piutang = Piutang::sum('sisa_piutang');
        $precision = $piutang >= 10000000 ? 3 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }

    public function getPiutangTotalByUser()
    {
        $piutang = Piutang::where('user_id', Auth::user()->id)->sum('jumlah_piutang');
        $precision = $piutang >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }

    public function getPiutangSisaHutangByUser()
    {
        $piutang = Piutang::where('user_id', Auth::user()->id)->sum('sisa_piutang');
        $precision = $piutang >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($piutang, precision: $precision);
        return $formattedNumber;
    }

    public function getTotalPiutangPerMonth($status = null, $years = null): array
    {
        $raw = Piutang::query()
            ->selectRaw('MONTH(tanggal_transaction) as month, SUM(jumlah_piutang) as total')
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($years, fn($q) => $q->whereYear('tanggal_transaction', $years))
            ->groupByRaw('MONTH(tanggal_transaction)')
            ->orderByRaw('MONTH(tanggal_transaction)')
            ->pluck('total', 'month')
            ->toArray();

        return $raw;
    }

    public function getTotalJumlahPiutangPerMonthByUser($status = null, $years = null): array
    {
        $raw = Piutang::query()
            ->where('user_id', Auth::user()->id)
            ->selectRaw('MONTH(tanggal_transaction) as month, SUM(jumlah_piutang) as total')
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($years, fn($q) => $q->whereYear('tanggal_transaction', $years))
            ->groupByRaw('MONTH(tanggal_transaction)')
            ->orderByRaw('MONTH(tanggal_transaction)')
            ->pluck('total', 'month')
            ->toArray();

        return $raw;
    }
    public function getTotalSisaPiutangPerMonthByUser($status = null, $years = null): array
    {
        $raw = Piutang::query()
            ->where('user_id', Auth::user()->id)
            ->selectRaw('MONTH(tanggal_transaction) as month, SUM(sisa_piutang) as total')
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($years, fn($q) => $q->whereYear('tanggal_transaction', $years))
            ->groupByRaw('MONTH(tanggal_transaction)')
            ->orderByRaw('MONTH(tanggal_transaction)')
            ->pluck('total', 'month')
            ->toArray();

        return $raw;
    }

    public function getFilteredQueryByUserPiutangs(
        ?string $search = null,
        ?string $status = null,
        ?string $productFilter = null,
        ?int $year = null,
        ?int $month = null,
        string $sortBy = 'newest'
    ): Builder {
        return Piutang::query()
            ->where('user_id', Auth::user()->id)
            ->with(['user', 'agreement', 'paymentPiutangs', 'products'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_piutang', 'like', '%' . $search . '%')
                        ->orWhere('nomor_faktur', 'like', '%' . $search . '%')
                        ->orWhere('nomor_order', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($productFilter !== '', function ($q) use ($productFilter) {
                $q->whereHas('products', function ($query) use ($productFilter) {
                    $query->where('piutang_products.product_id', $productFilter);
                });
            })
            ->when($year, fn($q) => $q->whereYear('tanggal_transaction', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_transaction', $month))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredByUserPiutangs(
        $search = null,
        $status = null,
        $productFilter = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryByUserPiutangs(
            $search,
            $status,
            $productFilter,
            $year,
            $month,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredByUserPiutangs(
        $search,
        $statusFilter,
        $productFilter,
        $year,
        $month,
        $sortBy = 'newest',
    ) {
        return $this->getFilteredQueryByUserPiutangs(
            $search,
            $statusFilter,
            $productFilter,
            $year,
            $month,
            $sortBy
        )->get();
    }
}
