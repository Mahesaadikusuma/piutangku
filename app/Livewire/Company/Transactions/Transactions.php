<?php

namespace App\Livewire\Company\Transactions;

use App\Exports\Transactions\TransactionExport;
use App\Helpers\Helpers;
use App\Models\Transaction;
use App\Repository\Interface\TransactionInterface;
use App\Repository\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('components.layouts.app')]
#[Title('Transactions')]
class Transactions extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';
    public $customerFilter = '';
    public $status = '';
    public $years = null;
    public $months = null;

    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => ['as' => 'q',],
            'customerFilter' => ['except' => '', 'as' => 'customer'],
            'status' => ['except' => ''],
            'years' => ['except' => null, 'as' => 'year'],
            'months' => ['except' => null, 'as' => 'month'],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage', 'customerFilter', 'status', 'years', 'months']);
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
                new TransactionExport(
                    $this->transactionRepo,
                    $this->search,
                    $this->customerFilter,
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
            session()->flash('error', 'Excel export failed.');
            return back();
        }
    }

    public function downloadPdf()
    {
        $piutang = collect();
        try {
            $now = Carbon::now();
            $transactions = $this->transactionRepo->paginateFilteredTransactions(
                $this->search,
                $this->customerFilter,
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

    #[Computed()]
    public function customers()
    {
        return DB::table('customers')
            ->join('users', 'customers.user_id', '=', 'users.id')
            ->select('customers.id', 'customers.code_customer', 'customers.user_id', 'users.name')
            ->get();
    }

    public function downloadPdfById($id)
    {
        $transaction = Transaction::with(['paymentPiutangs', 'user', 'piutang.agreement'])->find($id);
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
        $transactions = $this->transactionRepo->paginateFilteredTransactions(
            $this->search,
            $this->customerFilter,
            $this->status,
            $this->years,
            $this->months,
            $this->sortBy,
            $this->perPage
        );
        $getYears = Helpers::getYears();
        $getMonths = Helpers::getMonths();
        return view('livewire.company.transactions.transactions', [
            'transactions' => $transactions,
            'getYears' => $getYears,
            'getMonths' => $getMonths
        ]);
    }
}
