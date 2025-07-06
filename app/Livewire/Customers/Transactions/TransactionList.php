<?php

namespace App\Livewire\Customers\Transactions;

use App\Exports\Transactions\transactionByUser;
use App\Helpers\Helpers;
use App\Models\Transaction;
use App\Repository\Interface\TransactionInterface;
use App\Repository\TransactionRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


#[Layout('components.layouts.app')]
#[Title('History Transactions')]
class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $status = '';
    public $years = null;
    public $months = null;

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => ['as' => 'q',],
            'status' => ['except' => ''],
            'years' => ['except' => null, 'as' => 'year'],
            'months' => ['except' => null, 'as' => 'month'],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage', 'status', 'years', 'months']);
        $this->resetPage();
    }

    protected TransactionInterface $transactionRepo;
    public function boot(TransactionInterface $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function downloadExcel()
    {
        try {
            $export = Excel::download(
                new transactionByUser(
                    $this->transactionRepo,
                    $this->search,
                    $this->status,
                    $this->years,
                    $this->months,
                    $this->sortBy,
                ),
                'Transactions.xlsx'
            );

            session()->flash('success', 'Excel exported successfully.');
            return $export;
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'Excel export failed.' . $e->getMessage());
            return back();
        }
    }

    public function downloadPdf()
    {
        $piutang = collect();
        try {
            $now = Carbon::now();
            $transactions = $this->transactionRepo->paginateFilteredByUserTransactions(
                $this->search,
                $this->status,
                $this->years,
                $this->months,
                $this->sortBy,
                $this->perPage
            );

            // Belum ada Viewnya
            $pdf = Pdf::loadView('pdf.transactions.transactions', [
                'transactions' => $transactions,
                'now' => $now
            ])->setPaper('a4', 'landscape');

            session()->flash('success', 'PDF exported successfully.');
            return response()->streamDownload(function () use ($pdf) {
                echo  $pdf->stream();
            }, 'Transactions.pdf');
        } catch (\Exception $e) {
            Log::info("Error: " . $e->getMessage());
            session()->flash('error', 'PDF export failed.');
            return back();
        }
    }

    public function downloadPdfById($id)
    {
        $transaction = $this->transactionRepo->findTransactionById($id);
        $now = Carbon::now()->translatedFormat('d F Y');
        $subtotal = $transaction->transaction_total;
        $ppnPersen = $transaction->piutang->ppn ?? 0;
        $tax = $subtotal * ($ppnPersen / 100);
        $total = $subtotal + $tax;
        $pdf = Pdf::loadView('pdf.transactions.transactionByUser', [
            'transaction' => $transaction,
            'now' => $now,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'ppnPersen' => $ppnPersen,
        ]);


        return response()->streamDownload(function () use ($pdf) {
            echo  $pdf->stream();
        }, 'Transaction.pdf');
    }


    public function render()
    {
        $transactions = $this->transactionRepo->paginateFilteredByUserTransactions(
            $this->search,
            $this->status,
            $this->years,
            $this->months,
            $this->sortBy,
            $this->perPage
        );
        $getYears = Helpers::getYears();
        $getMonths = Helpers::getMonths();
        return view('livewire.customers.transactions.transaction-list', [
            'transactions' => $transactions,
            'getYears' => $getYears,
            'getMonths' => $getMonths
        ]);
    }
}
