<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format((float) ($expression), 0, ',', '.'); ?>";
        });

        View::composer('layouts.app', function ($view) {
            $cart = session('cart', []);
            $cartCount = collect($cart)->sum('quantity');

            try {
                $navCategories = Category::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->take(8)
                    ->get();
            } catch (Throwable $exception) {
                $navCategories = collect();
            }

            $view->with(compact('cartCount', 'navCategories'));
        });
    }
}