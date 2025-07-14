<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Piutang;
use App\Enums\StatusType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repository\Interface\PiutangInterface;
use App\Service\PiutangService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PiutangProductsController extends Controller
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

            $piutangs = $this->piutangRepo->paginateFilteredProducts(
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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'nomor_faktur' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_faktur',
            'nomor_order' => 'nullable|string|min:3|max:100|unique:piutangs,nomor_order',
            'ppn' => 'required|integer|min:0|max:100',
            'terms' => 'required|integer|min:1|max:365',
            'tanggal_transaction' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_transaction',
            'piutang_products' => 'required|array|min:1',
            'piutang_products.*.product_id' => 'required|exists:products,id',
            'piutang_products.*.qty' => 'required|integer|min:1',
            'piutang_products.*.price' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = collect($request->piutang_products)->sum(function ($product) {
                return $product['qty'] * $product['price'];
            });
            $ppnAmount = ($request->ppn / 100) * $subtotal;
            $jumlahPiutang = $subtotal + $ppnAmount;
            $dataPiutang = [
                'user_id' => $request->user_id,
                'nomor_faktur' => $request->nomor_faktur,
                'nomor_order' => $request->nomor_order,
                'jumlah_piutang' => $jumlahPiutang,
                'ppn' => $request->ppn,
                'terms' => $request->terms,
                'tanggal_transaction' => $request->tanggal_transaction,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ];

            $piutang = $this->piutangRepo->createPiutang($dataPiutang);
            foreach ($request->piutang_products as $product) {
                $piutang->products()->attach($product['product_id'], [
                    'qty' => $product['qty'],
                    'price' => $product['price'],
                ]);
            }
            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "Piutang berhasil dibuat",
                "data" => $piutang->load('products')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
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

    public function update(Request $request, Piutang $piutang)
    {
        // $request->validate([
        //     'nomor_faktur' => "nullable|string|min:3|max:100|unique:piutangs,nomor_faktur,{$piutang->id}",
        //     'nomor_order' => "nullable|string|min:3|max:100|unique:piutangs,nomor_order,{$piutang->id}",
        //     'ppn' => 'required|integer|min:0|max:100',
        //     'terms' => 'required|integer|min:1|max:365',
        //     'tanggal_transaction' => 'required|date',
        //     'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_transaction',
        //     'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        //     'piutang_products' => 'required|array|min:1',
        //     'piutang_products.*.product_id' => 'required|exists:products,id',
        //     'piutang_products.*.qty' => 'required|integer|min:1',
        //     'piutang_products.*.price' => 'required|numeric|min:1',
        //     'status_pembayaran' => ['required', Rule::in(array_column(StatusType::cases(), 'value'))],
        // ]);
        $id = $piutang->id;
        $validated = $request->validate([
            'nomor_faktur' => [
                'nullable',
                'string',
                'min:3',
                'max:100',
                Rule::unique('piutangs', 'nomor_faktur')->ignore($id),
            ],
            'nomor_order' => [
                'nullable',
                'string',
                'min:3',
                'max:100',
                Rule::unique('piutangs', 'nomor_order')->ignore($id),
            ],
            'ppn' => 'required|integer|min:0|max:100',
            'terms' => 'required|integer|min:1|max:365',
            'tanggal_transaction' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_transaction',
            'status_pembayaran' => ['required', Rule::in(array_column(StatusType::cases(), 'value'))],
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'piutang_products' => 'required|array|min:1',
            'piutang_products.*.product_id' => 'required|exists:products,id',
            'piutang_products.*.qty' => 'required|integer|min:1',
            'piutang_products.*.price' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = collect($request->piutang_products)
                ->sum(fn($item) => $item['qty'] * $item['price']);

            $ppnAmount = ($request->ppn / 100) * $subtotal;
            $jumlahPiutang = $subtotal + $ppnAmount;

            $sisaPiutang = $piutang->status_pembayaran === StatusType::SUCCESS->value
                ? 0 : $jumlahPiutang;

            if (
                $piutang->status_pembayaran === StatusType::SUCCESS->value &&
                $request->status_pembayaran !== $piutang->status_pembayaran
            ) {
                throw new \Exception("Status tidak bisa diubah setelah SUCCESS.");
            }

            $data = [
                'user_id' => $request->user_id,
                'nomor_faktur' => $request->nomor_faktur,
                'nomor_order' => $request->nomor_order,
                'jumlah_piutang' => $jumlahPiutang,
                'ppn' => $request->ppn,
                'terms' => $request->terms,
                'status_pembayaran' => $request->status_pembayaran,
                'sisa_piutang' => $sisaPiutang,
                'tanggal_transaction' => $request->tanggal_transaction,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            ];

            if ($request->proof) {
                $data['bukti_pembayaran'] = $request->proof;
            }

            $this->piutangService->updatePiutangProduct(
                $piutang,
                $request->piutang_products,
                $data
            );

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $piutang->load('products'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request, Piutang $piutang)
    {
        try {
            $piutang->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Piutang berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
