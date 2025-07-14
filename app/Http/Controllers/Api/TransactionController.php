<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Repository\Interface\TransactionInterface;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionInterface $transactionRepo;
    public function __construct(TransactionInterface $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function transactions(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');
            $customerFilter = $request->input('customerFilter', '');
            $status = $request->input('status', '');
            $years = $request->input('years', null);
            $months = $request->input('months', null);

            $transactions = $this->transactionRepo->paginateFilteredTransactions(
                $search,
                $customerFilter,
                $status,
                $years,
                $months,
                $sortBy,
                $perPage
            );

            return response()->json([
                "status" => "success",
                "data" => $transactions
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
