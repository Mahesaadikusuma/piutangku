<?php

namespace App\Exports\Transactions;

use App\Repository\TransactionRepository;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class transactionByUser implements FromView, ShouldAutoSize
{
    protected TransactionRepository $transactionRepo;
    protected $search;
    protected $status;
    protected $years;
    protected $months;
    protected $sortBy;
    protected $filteredCount = 0;

    public function __construct(
        TransactionRepository $transactionRepo,
        $search = null,
        $status = null,
        $years = null,
        $months = null,
        $sortBy = 'newest'
    ) {
        $this->transactionRepo = $transactionRepo;
        $this->search = $search;
        $this->status = $status;
        $this->years = $years;
        $this->months = $months;
        $this->sortBy = $sortBy;
    }

    public function view(): View
    {
        $transactions = $this->transactionRepo
            ->allFilteredByUserTransactions(
                $this->search,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy
            );
        return view('excel.transactions.transactionByUser', [
            'transactions' => $transactions,
        ]);
    }
}
