<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productsCount' => Product::count(),
            'categoriesCount' => Category::count(),
            'ordersCount' => Order::count(),
            'usersCount' => User::where('is_admin', false)->count(),
            'paidRevenue' => Order::where('payment_status', 'paid')->sum('total'),
            'pendingOrdersCount' => Order::where('status', 'pending')->count(),
            'lowStockCount' => Product::where('stock', '<=', 5)->count(),
            'latestOrders' => Order::with('user')->latest()->take(6)->get(),
            'lowStockProducts' => Product::with('category')->where('stock', '<=', 5)->orderBy('stock')->take(6)->get(),
        ]);
    }
}
