<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
