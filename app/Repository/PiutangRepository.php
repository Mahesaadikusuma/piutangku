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

        // $query = Piutang::query()
        // ->doesntHave('products')
        // ->with(['user', 'agreement', 'paymentPiutangs'])
        // ->when($this->search, function ($query) {
        // $query->where(function ($q) {
        // $q->where('kode_piutang', 'like', '%' . $this->search . '%')
        // ->orWhere('nomor_faktur', 'like', '%' . $this->search . '%')
        // ->orWhere('nomor_order', 'like', '%' . $this->search . '%')
        // ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $this->search . '%'));
        // });
        // })
        // ->when($this->customerFilter !== '', fn($q) => $q->where('user_id', $this->customerFilter))
        // ->when($this->status !== '', fn($q) => $q->where('status_pembayaran', $this->status))
        // ->when($this->sortBy === 'latest', fn($q) => $q->oldest())
        // ->when($this->sortBy === 'newest', fn($q) => $q->latest());

        // $piutangs = $query->paginate($this->perPage);
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
            ->when($status !== '', fn($q) => $q->where('status_pembayaran', $status))
            ->when($year, fn($q) => $q->whereYear('tanggal_transaction', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_transaction', $month))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredProducts(
        $search = null,
        $customerFilter = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryProducts(
            $search,
            $customerFilter,
            $status,
            $year,
            $month,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredProducts(
        $search,
        $customerFilter,
        $statusFilter,
        $year,
        $month,
        $sortBy = 'newest',
    ) {
        return $this->getFilteredQueryProducts(
            $search,
            $customerFilter,
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

    public function agePiutangPerCustomer(int $limit = 10, ?string $search = null)
    {
        $today = Carbon::now()->toDateString();

        // Subquery untuk menghitung total_piutang yang belum dibayar per user_id
        $subqueryTotalPiutang = DB::table('piutangs')
            ->select('user_id', DB::raw('SUM(jumlah_piutang) as total_piutang_per_user'))
            ->where('status_pembayaran', StatusType::PENDING->value)
            ->groupBy('user_id');

        $query = DB::table('piutangs')
            ->join('users', 'piutangs.user_id', '=', 'users.id')
            ->join('customers', 'customers.user_id', '=', 'users.id')
            ->leftJoin('transactions', function ($join) {
                // Pastikan join transactions hanya untuk piutang yang relevan
                $join->on('piutangs.id', '=', 'transactions.piutang_id')
                    ->where('transactions.status', StatusType::SUCCESS->value); // Filter transaksi yang sukses saja di sini
            })
            // Gabungkan dengan subquery total piutang per user
            ->joinSub($subqueryTotalPiutang, 'total_pending_piutangs', function ($join) {
                $join->on('users.id', '=', 'total_pending_piutangs.user_id');
            })
            ->where('piutangs.status_pembayaran', StatusType::PENDING->value)
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'customers.id as customer_id',
                'total_pending_piutangs.total_piutang_per_user as total_piutang', // Ambil dari subquery
                DB::raw("
                    SUM(CASE
                        WHEN DATEDIFF('$today', piutangs.tanggal_jatuh_tempo) BETWEEN 0 AND 30
                        THEN IFNULL(transactions.transaction_total, 0) ELSE 0 END
                    ) as age_0_30
                "),
                DB::raw("
                    SUM(CASE
                        WHEN DATEDIFF('$today', piutangs.tanggal_jatuh_tempo) BETWEEN 31 AND 60
                        THEN IFNULL(transactions.transaction_total, 0) ELSE 0 END
                    ) as age_31_60
                "),
                DB::raw("
                    SUM(CASE
                        WHEN DATEDIFF('$today', piutangs.tanggal_jatuh_tempo) BETWEEN 61 AND 90
                        THEN IFNULL(transactions.transaction_total, 0) ELSE 0 END
                    ) as age_61_90
                "),
                DB::raw("
                    SUM(CASE
                        WHEN DATEDIFF('$today', piutangs.tanggal_jatuh_tempo) > 90
                        THEN IFNULL(transactions.transaction_total, 0) ELSE 0 END
                    ) as age_90_plus
                ")
            )
            // Group by semua kolom non-agregasi, termasuk yang dari subquery
            ->groupBy('users.id', 'users.name', 'customers.id', 'total_pending_piutangs.total_piutang_per_user');

        if ($search) {
            $query->where('users.name', 'like', '%' . $search . '%');
        }

        return $query->paginate($limit);
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

    public function getFilteredQueryByUserPiutangs(
        ?string $search = null,
        ?string $status = null,
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
            ->when($year, fn($q) => $q->whereYear('tanggal_transaction', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_transaction', $month))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredByUserPiutangs(
        $search = null,
        $status = null,
        $year = null,
        $month = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryByUserPiutangs(
            $search,
            $status,
            $year,
            $month,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredByUserPiutangs(
        $search,
        $statusFilter,
        $year,
        $month,
        $sortBy = 'newest',
    ) {
        return $this->getFilteredQueryByUserPiutangs(
            $search,
            $statusFilter,
            $year,
            $month,
            $sortBy
        )->get();
    }
}
