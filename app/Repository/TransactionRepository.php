<?php

namespace App\Repository;

use App\Enums\StatusType;
use App\Models\Transaction;
use App\Repository\Interface\TransactionInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class TransactionRepository implements TransactionInterface
{
    public function findTransactionById($id)
    {
        return Transaction::with(['paymentPiutangs', 'user', 'piutang.products', 'piutang.agreement'])->find($id);
    }

    public function getFilteredQueryTransactions(
        ?string $search = null,
        ?string $customerFilter = null,
        ?string $status = null,
        ?int $years = null,
        ?int $months = null,
        string $sortBy = 'newest'
    ): Builder {
        return Transaction::query()
            ->with(['paymentPiutangs', 'user', 'piutang.products', 'piutang.agreement'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($customerFilter !== '', fn($q) => $q->where('user_id', $customerFilter))
            ->when($status !== '', fn($q) => $q->where('status', $status))
            ->when($years, fn($q) => $q->whereYear('created_at', $years))
            ->when($months, fn($q) => $q->whereMonth('created_at', $months))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredTransactions(
        $search = null,
        $customerFilter = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryTransactions(
            $search,
            $customerFilter,
            $status,
            $years,
            $months,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredTransactions(
        $search,
        $customerFilter,
        $statusFilter,
        $years,
        $months,
        $sortBy
    ) {
        return $this->getFilteredQueryTransactions(
            $search,
            $customerFilter,
            $statusFilter,
            $years,
            $months,
            $sortBy
        )->get();
    }



    public function createTransaction(array $data): Transaction
    {
        return Transaction::create([
            'user_id' => $data['user_id'],
            "piutang_id" => $data['piutang_id'],
            'kode' => $this->generateKodeTransaction(),
            'transaction_total' => $data['transaction_total'],
            'jenis_pembayaran' => $data['jenis_pembayaran'] ?? null,
            'status' => $data['status'] ?? StatusType::PENDING->value,
            'proof' => $data['proof'] ?? null,
            'midtrans_url' => $data['midtrans_url'] ?? null,
        ]);
    }

    public function generateKodeTransaction(): string
    {
        $currentDate = Carbon::now()->format('Ym');
        $prefix = "$currentDate-LL-";

        $lastTransaction = Transaction::where('kode', 'LIKE', "$prefix%")
            ->orderBy('kode', 'desc')
            ->first();

        $lastNumber = $lastTransaction
            ? (int) substr($lastTransaction->kode, -4)
            : 0;

        do {
            $newNumber = str_pad(++$lastNumber, 4, '0', STR_PAD_LEFT);
            $kodePayment = $prefix . $newNumber;
        } while (Transaction::where('kode', $kodePayment)->exists());

        return $kodePayment;
    }

    public function getCountTransactions()
    {
        $transaction = Transaction::count();
        $precision = $transaction >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($transaction, precision: $precision);
        return $formattedNumber;
    }

    public function getCountTransactionsByUser()
    {
        $transaction = Transaction::where('user_id', Auth::user()->id)->count();
        $precision = $transaction >= 10000000 ? 1 : 0;
        $formattedNumber  = Number::abbreviate($transaction, precision: $precision);
        return $formattedNumber;
    }

    public function getFilteredQueryByUserTransactions(
        ?string $search = null,
        ?string $status = null,
        ?int $years = null,
        ?int $months = null,
        string $sortBy = 'newest'
    ): Builder {
        return Transaction::query()
            ->where('user_id', Auth::user()->id)
            ->with(['paymentPiutangs', 'user', 'piutang.products', 'piutang.agreement'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', '%' . $search . '%')
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($status !== '', fn($q) => $q->where('status', $status))
            ->when($years, fn($q) => $q->whereYear('created_at', $years))
            ->when($months, fn($q) => $q->whereMonth('created_at', $months))
            ->when($sortBy === 'latest', fn($q) => $q->oldest())
            ->when($sortBy === 'newest', fn($q) => $q->latest());
    }

    public function paginateFilteredByUserTransactions(
        $search = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest',
        $perPage = 10
    ) {
        return $this->getFilteredQueryByUserTransactions(
            $search,
            $status,
            $years,
            $months,
            $sortBy,
        )->paginate($perPage);
    }

    public function allFilteredByUserTransactions(
        $search,
        $statusFilter,
        $years,
        $months,
        $sortBy
    ) {
        return $this->getFilteredQueryByUserTransactions(
            $search,
            $statusFilter,
            $years,
            $months,
            $sortBy
        )->get();
    }
}
