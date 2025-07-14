<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function products(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');
            $categoryFilter = $request->input('categoryFilter', null);

            $products = Product::query()
                ->with(['category'])
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->when($categoryFilter !== null && $categoryFilter !== '', function ($query) use ($categoryFilter) {
                    $query->where('category_id', $categoryFilter);
                })
                ->when($sortBy === 'latest', fn($q) => $q->oldest())
                ->when($sortBy === 'newest', fn($q) => $q->latest())
                ->paginate($perPage);

            return response()->json([
                "status" => "success",
                "data" => $products
            ]);
        } catch (\Exception $e) {

            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|unique:products,kode_product|min:3|max:100',
                'name' => 'required|string|min:3|max:100',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string|min:5',
                'price' => 'required',
                'stock' => 'required',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);

            if ($request->thumbnail) {
                $thumbnailPath = $request->thumbnail->storeAs('category/thumbnail', $request->thumbnail->hashName(), 'public');
            }

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'kode_product' => $request->code,
                'description' => $request->description,
                'thumbnail' => $thumbnailPath ?? null,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            $product = Product::with('category')->create($data);
            return response()->json([
                "status" => "success",
                'message' => 'Product berhasil dibuat',
                "data" => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'code' => 'required|string|min:3|max:100|unique:products,kode_product,' . $id,
                'name' => 'required|string|min:3|max:100',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string|min:5',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);

            $product = Product::with('category')->findOrFail($id);

            $thumbnailPath = $product->thumbnail;

            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                    Storage::disk('public')->delete($product->thumbnail);
                }

                // Simpan thumbnail baru
                $thumbnailPath = $request->file('thumbnail')->storeAs(
                    'product/image',
                    $request->file('thumbnail')->hashName(),
                    'public'
                );
            }

            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'kode_product' => $request->code,
                'description' => $request->description,
                'thumbnail' => $thumbnailPath,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            $product->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Product berhasil diperbarui',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus'
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
