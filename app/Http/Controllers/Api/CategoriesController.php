<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repository\Interface\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    protected CategoryInterface $categoryRepo;
    public function __construct(CategoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function categories(Request $request)
    {
        try {
            $id = $request->input('id');
            $search = $request->input('search', '');
            $perPage = $request->input('perPage', 10);
            $sortBy = $request->input('sortBy', 'newest');

            $categories = $this->categoryRepo->paginateFiltered($search, $sortBy, $perPage);
            return response()->json([
                "status" => "success",
                "data" => $categories
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
                'name' => 'required|string|min:3|max:100',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);

            if ($request->thumbnail) {
                $thumbnailPath = $request->thumbnail->storeAs('category/thumbnail', $request->thumbnail->hashName(), 'public');
            }

            $data = [
                'name' => $request->name,
                'thumbnail' => $thumbnailPath ?? null
            ];

            $category = $this->categoryRepo->createCategory($data);

            return response()->json([
                "status" => "success",
                "data" => $category
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
                'name' => 'required|string|min:3|max:100',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            ]);

            $category = Category::findOrFail($id);

            $data = ['name' => $request->name];
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->thumbnail;
            }
            $this->categoryRepo->updateCategory($data, $category);
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function delete(Request $request, Category $category)
    {
        try {
            $this->categoryRepo->deleteCategory($category);

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
