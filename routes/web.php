<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Catalog
|--------------------------------------------------------------------------
| Customer tidak perlu login. Halaman publik hanya untuk melihat katalog,
| detail produk, dan menghubungi admin.
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::view('/contact', 'contact', ['title' => 'Contact'])->name('contact');
Route::post('/contact', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'message' => ['required', 'string', 'max:1000'],
    ]);

    return back()->with('success', 'Pesan berhasil dikirim. Pada demo ini pesan disimpan sebagai notifikasi session.');
})->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Admin Authentication Only
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::redirect('/register', '/login')->name('register');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Legacy E-Commerce Routes Disabled
|--------------------------------------------------------------------------
| Route lama tetap diberi nama agar tidak error bila ada link lama, tetapi
| diarahkan ke katalog/contact karena versi Nextronix tidak memakai customer
| login, cart, atau checkout.
*/
Route::get('/cart', fn () => redirect()->route('shop')->with('info', 'Nextronix sekarang memakai mode katalog. Silakan lihat produk dan hubungi admin untuk pemesanan.'))->name('cart.index');
Route::match(['post'], '/cart/add/{product:slug}', fn () => redirect()->route('shop')->with('info', 'Untuk pemesanan, silakan hubungi admin.'))->name('cart.add');
Route::match(['patch'], '/cart/update/{product:id}', fn () => redirect()->route('shop'))->name('cart.update');
Route::match(['delete'], '/cart/remove/{product:id}', fn () => redirect()->route('shop'))->name('cart.remove');
Route::post('/cart/clear', fn () => redirect()->route('shop'))->name('cart.clear');
Route::get('/checkout', fn () => redirect()->route('contact')->with('info', 'Checkout customer dinonaktifkan. Pemesanan dilakukan melalui admin/contact.'))->name('checkout.show');
Route::post('/checkout', fn () => redirect()->route('contact'))->name('checkout.store');

/* Payment callbacks are kept for compatibility with existing admin/order code. */
Route::post('/payments/midtrans/notification', [PaymentController::class, 'notification'])->name('payment.midtrans.notification');
Route::get('/payments/midtrans/finish', [PaymentController::class, 'finish'])->name('payment.midtrans.finish');
Route::get('/payments/midtrans/unfinish', [PaymentController::class, 'unfinish'])->name('payment.midtrans.unfinish');
Route::get('/payments/midtrans/error', [PaymentController::class, 'error'])->name('payment.midtrans.error');

Route::middleware('auth')->group(function () {
    Route::get('/invoice/{order:order_number}', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order:order_number}', [AccountController::class, 'showOrder'])->name('account.orders.show');
});

Route::redirect('/cheackout', '/contact');
Route::redirect('/cart.html', '/shop');
Route::redirect('/shop.html', '/shop');
Route::redirect('/contact.html', '/contact');

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:access admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class)->except('show')->middleware('permission:manage categories');
    Route::resource('products', AdminProductController::class)->except('show')->middleware('permission:manage products');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index')->middleware('permission:manage orders');
    Route::get('orders/{order:order_number}', [AdminOrderController::class, 'show'])->name('orders.show')->middleware('permission:manage orders');
    Route::patch('orders/{order:order_number}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status')->middleware('permission:manage orders');
    Route::resource('users', AdminUserController::class)->except('show')->middleware('permission:manage users');
    Route::resource('roles', AdminRoleController::class)->except('show')->middleware('permission:manage roles');
});
