<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductOrderController;

Route::get('/changeLocale/{locale}', function (string $locale) {
    Log::info('Attempting to change locale to: ' . $locale);
    if (in_array($locale, ['en', 'es', 'fr', 'ar'])) {
        Log::info('Locale before setting: ' . session('locale'));
        session()->put('locale', $locale);
        Log::info('Locale after setting: ' . session('locale'));
    }
    return redirect()->back();
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('customers')->group(function () {
    Route::get('/', [DashboardController::class, 'customers'])->name('customers.index');
    Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('/{customer}/delete', [CustomerController::class, 'delete'])->name('customers.delete');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/search/{term}', [CustomerController::class, 'searchTerm'])->name('customers.search.term');
    Route::get('/{customer}/orders', [OrderController::class, 'getCustomerOrders'])->name('customers.orders');
});

Route::prefix('suppliers')->group(function () {
    Route::get('/', [DashboardController::class, 'suppliers'])->name('suppliers.index');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('api.products.show');
    Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/by-category', [CategoryController::class, 'productsByCategory'])->name('products.by.category');
    Route::get('/by-category/{category}', [CategoryController::class, 'getProductsByCategory'])->name('products.filter.by.category');
    Route::get('/by-supplier', [DashboardController::class, 'productsBySupplier'])->name('products.by.supplier');
    Route::get('/api/by-supplier/{supplier}', [DashboardController::class, 'getProductsBySupplier'])->name('api.products.by.supplier');
    Route::get('/by-store', [DashboardController::class, 'productsByStore'])->name('products.by.store');
    Route::get('/api/by-store/{store}', [DashboardController::class, 'getProductsByStore'])->name('api.products.by.store');

    Route::get('/orders-count', [ProductController::class, 'ordersCount'])->name('products.orders_count');
    Route::get('/more-than-6-orders', [ProductController::class, 'productsMoreThan6Orders'])->name('products.more_than_6_orders');
});

Route::prefix('orders')->group(function () {
    Route::get('/', [DashboardController::class, 'orders'])->name('orders.index');
    Route::get('/{order}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details');
    Route::get('/totals', [OrderController::class, 'orderTotals'])->name('orders.totals');
    Route::get('/greater-than-60', [OrderController::class, 'ordersGreaterThanOrder60'])->name('orders.greater_than_60');
});

Route::get('/ordered-products', [ProductOrderController::class, 'index'])->name('ordered.products');
Route::get('/same-products-customers', [CustomerController::class, 'sameProductsCustomers'])->name('same.products.customers');
