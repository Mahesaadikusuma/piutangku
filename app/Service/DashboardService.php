<?php

namespace App\Service;

use App\Repository\PiutangRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;

class DashboardService
{
    protected PiutangRepository $piutangRepository;
    protected UserRepository $userRepository;
    protected TransactionRepository $transactionRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(PiutangRepository $piutangRepository, UserRepository $userRepository, TransactionRepository $transactionRepository)
    {
        $this->piutangRepository = $piutangRepository;
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function DashboardAnalytics()
    {
        $countUsers = $this->userRepository->getCountUsers();
        $countTransactions = $this->transactionRepository->getCountTransactions();
        $countPiutang = $this->piutangRepository->getPiutangCount();
        $totalPiutang = $this->piutangRepository->getTotalPiutang();
        $totalSisaPiutang = $this->piutangRepository->getTotalSisaPiutang();
        $countProductsPiutang = $this->piutangRepository->getPiutangProductCounts();
        $getPiutangTotals = $this->piutangRepository->getPiutangTotals();
        return [
            'totalUsers' => $countUsers,
            'totalTransactions' => $countTransactions,
            'countPiutang' => $countPiutang,
            'totalPiutang' => $totalPiutang,
            'totalSisaPiutang' => $totalSisaPiutang,
            'countProductsPiutang' => $countProductsPiutang,
            'getPiutangTotals' => $getPiutangTotals
        ];
    }

    public function DashboardAnalyticsCustomer()
    {
        $countPiutang = $this->piutangRepository->getPiutangCountByUser();
        $countTransactions = $this->transactionRepository->getCountTransactionsByUser();
        $totalPiutang = $this->piutangRepository->getPiutangTotalByUser();
        $totalSisaPiutang = $this->piutangRepository->getPiutangSisaHutangByUser();
        $getPiutangTotalsByUser = $this->piutangRepository->getPiutangTotalsByUser();
        return [
            'totalPiutangCount' => $countPiutang,
            'totalTransactionsCount' => $countTransactions,
            'totalJumlahPiutang' => $totalPiutang,
            'totalSisaPiutang' => $totalSisaPiutang,
            'getPiutangTotalsByUser' => $getPiutangTotalsByUser
        ];
    }


    public function agePiutangCustomer($limit = 25, $search)
    {
        return $this->piutangRepository->agePiutangPerCustomerPaginate($limit, $search);
    }
}
