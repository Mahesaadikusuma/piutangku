<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Piutang;
use App\Enums\StatusType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Repository\Interface\PiutangInterface;
use App\Service\PiutangService;

class PiutangController extends Controller
{

    protected PiutangInterface $piutangRepo;
    protected PiutangService $piutangService;
    public function __construct(PiutangInterface $piutangRepo, PiutangService $piutangService)
    {
        $this->piutangRepo = $piutangRepo;
        $this->piutangService = $piutangService;
    }

    public function piutangs(Request $request)
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

            $piutangs = $this->piutangRepo->paginateFilteredNotProducts(
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
                "data" => $piutangs
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function show(Request $request, Piutang $piutang)
    {
        try {
            $piutang->load([
                'user.setting',
                'user.customer',
                'products',
                'paymentPiutangs.transaction',
            ]);
            return response()->json([
                "status" => "success",
                "data" => $piutang,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'nomor_faktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur',
                'nomor_order' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order',
                'user_id' => 'required|exists:users,id',
                'jumlah_piutang' => 'required|min:1',
                'ppn' => 'required|integer|min:0',
                'terms' => 'required|integer|min:10',
                'tanggal_transaction' => 'required|date',
                'tanggal_jatuh_tempo' => 'required|date|after_or_equal:awalTempo',
            ]);

            $jumlahFaktur = $request->jumlah_piutang;
            $ppn = $request->ppn;

            $ppnAmount = ($ppn / 100) * $jumlahFaktur;
            $jumlahPiutang = $jumlahFaktur + $ppnAmount;

            $data = [
                'user_id' => $request->user_id,
                'nomor_faktur' => $request->nomor_faktur,
                'nomor_order' => $request->nomor_order,
                'jumlah_piutang' => $jumlahPiutang,
                'ppn' => $request->ppn,
                'terms' => $request->terms,
                'tanggal_transaction' => $request->tanggal_transaction,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ];

            $piutang = $this->piutangRepo->createPiutang($data);
            return response()->json([
                "status" => "success",
                "data" => $piutang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nomor_faktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur,' . $id,
                'nomor_order' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order,' . $id,
                'user_id' => 'required|exists:users,id',
                'jumlah_piutang' => 'required|min:1',
                'ppn' => 'required|integer|min:0',
                'terms' => 'required|integer|min:10',
                'tanggal_transaction' => 'required|date',
                'tanggal_jatuh_tempo' => 'required|date|after_or_equal:awalTempo',
            ]);

            $piutang = Piutang::findOrFail($id);
            $jumlahFaktur = $request->jumlah_piutang;
            $ppn = $request->ppn;

            $ppnAmount = ($ppn / 100) * $jumlahFaktur;
            $jumlahPiutang = $jumlahFaktur + $ppnAmount;

            if ($piutang->status_pembayaran === StatusType::SUCCESS->value) {
                $piutang->sisa_piutang = 0;
            } else {
                $piutang->sisa_piutang = $jumlahPiutang;
            }

            $data = [
                'user_id' => $request->user_id,
                'nomor_faktur' => $request->nomor_faktur,
                'nomor_order' => $request->nomor_order,
                'jumlah_piutang' => $jumlahPiutang,
                'ppn' => $request->ppn,
                'terms' => $request->terms,
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal_transaction' => $request->tanggal_transaction,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ];

            if ($request->buktiPembayaran) {
                $data['bukti_pembayaran'] = $request->buktiPembayaran;
            }

            if ($request->statusPembayaran === StatusType::SUCCESS->value) {
                $data['tanggal_lunas'] = now();
            }
            $this->piutangService->updatePiutang($piutang, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Piutang berhasil diperbarui',
                'data' => $piutang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(Request $request, Piutang $piutang)
    {
        try {
            $piutang->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Piutang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
