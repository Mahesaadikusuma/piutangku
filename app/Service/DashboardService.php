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

        return [
            'totalUsers' => $countUsers,
            'totalTransactions' => $countTransactions,
            'countPiutang' => $countPiutang,
            'totalPiutang' => $totalPiutang,
            'totalSisaPiutang' => $totalSisaPiutang
        ];
    }

    public function DashboardAnalyticsCustomer()
    {
        $countPiutang = $this->piutangRepository->getPiutangCountByUser();
        $countTransactions = $this->transactionRepository->getCountTransactionsByUser();
        $totalPiutang = $this->piutangRepository->getPiutangTotalByUser();
        $totalSisaPiutang = $this->piutangRepository->getPiutangSisaHutangByUser();
        return [
            'totalPiutangCount' => $countPiutang,
            'totalTransactionsCount' => $countTransactions,
            'totalJumlahPiutang' => $totalPiutang,
            'totalSisaPiutang' => $totalSisaPiutang,
        ];
    }


    public function agePiutangCustomer($limit = 10, $search)
    {
        return $this->piutangRepository->agePiutangPerCustomer($limit, $search);
    }
}
