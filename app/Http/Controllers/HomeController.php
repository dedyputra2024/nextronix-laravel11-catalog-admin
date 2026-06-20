<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->with('category')
            ->active()
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with('category')
            ->active()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::query()
            ->withCount(['activeProducts as products_count'])
            ->where('is_active', true)
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts', 'latestProducts', 'categories'));
    }
}
