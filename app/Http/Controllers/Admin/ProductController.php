<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->boolean('low_stock')) {
            $query->where('stock', '<=', 5);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['image'] = $this->handleImageUpload($request) ?: ($validated['image'] ?? 'img/product-1.png');

        unset($validated['image_file']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $uploadedImage = $this->handleImageUpload($request);
        if ($uploadedImage) {
            $validated['image'] = $uploadedImage;
        } elseif (! isset($validated['image'])) {
            $validated['image'] = $product->image;
        }

        unset($validated['image_file']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function handleImageUpload(Request $request): ?string
    {
        if (! $request->hasFile('image_file')) {
            return null;
        }

        return $request->file('image_file')->store('products', 'public');
    }
}
