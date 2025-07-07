<?php

namespace App\Exports\Transactions;

use App\Models\Transaction;
use App\Repository\TransactionRepository;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class TransactionExport implements FromView, ShouldAutoSize
{
    protected TransactionRepository $transactionRepo;
    protected $search;
    protected $customerFilter;
    protected $status;
    protected $years;
    protected $months;
    protected $sortBy;
    protected $filteredCount = 0;


    public function __construct(
        TransactionRepository $transactionRepo,
        $search = null,
        $customerFilter = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest'
    ) {
        $this->transactionRepo = $transactionRepo;
        $this->search = $search;
        $this->customerFilter = $customerFilter;
        $this->status = $status;
        $this->years = $years;
        $this->months = $months;
        $this->sortBy = $sortBy;
    }

    public function view(): View
    {
        $transactions = $this->transactionRepo
            ->allFilteredTransactions(
                $this->search,
                $this->customerFilter,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy
            );
        Log::info("message", $transactions->toArray());
        return view('excel.transactions.transaction', [
            'transactions' => $transactions,
        ]);
    }
}
