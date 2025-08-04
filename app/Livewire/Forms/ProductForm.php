<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use App\Repository\Interface\ProductInterface;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class ProductForm extends Form
{
    use WithFileUploads;
    public ?Product $product;

    #[Validate()]
    public $code;
    public $name;
    public $thumbnail;
    public $category_id;
    public $description;
    public $price;
    public $stock;


    protected ProductInterface $productRepository;

    public function boot(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    protected function rules()
    {
        return [
            'code' => 'required|string|unique:products,kode_product|min:3|max:100',
            'name' => 'required|string|min:3|max:100',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|min:5',
            'price' => 'required',
            'stock' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ];
    }

    protected function messages()
    {
        return [
            'code.required' => 'Kode product  harus di isi.',
            'code.min' => 'Kode product Minimal characters 3 huruf.',
            'code.max' => 'Kode product terlalu panjang maximal 100 huruf.',
            'code.unique' => 'Kode product sudah ada.',
            'name.required' => 'product name harus di isi.',
            'name.min' => 'product Minimal characters 3 huruf.',
            'name.max' => 'product name terlalu panjang maximal 100 huruf.',
            'category_id.required' => 'category harus di isi.',
            'description.min' => 'product description minimal 5 huruf.',
            'price.required' => 'product price harus di isi.',
            'stock.required' => 'product stock harus di isi.',
            'thumbnail.image' => 'thumbnail Harus di isi image.',
            'thumbnail.mimes' => 'thumbnail Harus di isi type image jpg,jpeg,png,svg.',
            'thumbnail.max' => 'thumbnail maximal file 2Mb.',
        ];
    }


    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->code = $product->kode_product;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock = $product->stock;
    }

    public function store()
    {
        try {
            $this->validate();
            $thumbnailPath = null;
            if ($this->thumbnail) {
                $thumbnailPath = $this->thumbnail->storeAs('product/image', $this->thumbnail->hashName(), 'public');
            }

            $product = $this->productRepository->createProduct([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'kode_product' => $this->code,
                'description' => $this->description,
                'thumbnail' => $thumbnailPath ?? null,
                'price' => $this->price,
                'stock' => $this->stock,
            ]);

            Log::info($product);
            $this->reset();
        } catch (\Exception $e) {
            // Toaster::error('validasi: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update()
    {
        try {
            $this->validate();
            $data = [
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
            ];

            if ($this->thumbnail) {
                $data['thumbnail'] = $this->thumbnail;
            }

            $this->productRepository->updateProduct($data, $this->product);
        } catch (\Exception $e) {
            // Toaster::error('Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function destroy()
    {
        $this->productRepository->deleteProduct($this->product);
    }
}
