<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category')->active();

        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        match ($request->get('sort')) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::query()->withCount('activeProducts')->where('is_active', true)->orderBy('name')->get();

        return view('shop', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load('category');
        $relatedProducts = Product::query()
            ->with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->take(4)
            ->get();

        return view('product-show', compact('product', 'relatedProducts'));
    }
}
