<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\OrderController;
use App\Http\Controllers\Tenant\CartController;
use App\Http\Controllers\Tenant\AddressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Super Admin Routes (admin.example.test)
Route::domain('admin.example.test')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('tenants', TenantController::class)->except(['index']);
    });

// Public Routes
Route::get('/', function () {
    if (Auth::check() && isTenantAdmin()) {
        return redirect()->route('tenant.dashboard', [
            'subdomain' => tenant('subdomain')
        ]);
    }

    if (Auth::check() && isSuperAdmin()) {
        return redirect()->away('http://admin.example.test:8000');
    }

    return view('welcome');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Tenant Routes ({subdomain}.example.test)
Route::domain('{subdomain}.example.test')
    ->middleware([
        'auth',
        'role:tenantadmin',
        \App\Http\Middleware\TenantScopeMiddleware::class
    ])
    ->group(function () {

        // Dashboard
        Route::get('/', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
        Route::get('/dashboard', [TenantController::class, 'dashboard']);

        // Products (Custom routes without model binding)
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('tenant.products.index');
            Route::get('/create', [ProductController::class, 'create'])->name('tenant.products.create');
            Route::post('/', [ProductController::class, 'store'])->name('tenant.products.store');
            Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('tenant.products.edit');
            Route::put('/{id}', [ProductController::class, 'update'])->name('tenant.products.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('tenant.products.destroy');
        });

        // Customers
        Route::resource('customers', CustomerController::class)
            ->parameter('customer', 'customer:id')
            ->names([
                'index' => 'tenant.customers.index',
                'create' => 'tenant.customers.create',
                'store' => 'tenant.customers.store',
                'show' => 'tenant.customers.show',
                'edit' => 'tenant.customers.edit',
                'update' => 'tenant.customers.update',
                'destroy' => 'tenant.customers.destroy'
            ]);

        // Customer Addresses (nested)
        Route::prefix('customers/{customer}')->group(function () {
            Route::resource('addresses', AddressController::class)->names([
                'index' => 'tenant.addresses.index',
                'create' => 'tenant.addresses.create',
                'store' => 'tenant.addresses.store',
                'show' => 'tenant.addresses.show',
                'edit' => 'tenant.addresses.edit',
                'update' => 'tenant.addresses.update',
                'destroy' => 'tenant.addresses.destroy'
            ]);
        });

        // Cart System
     // Cart (Custom routes without model binding, like products)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('tenant.cart.index'); // View cart
    Route::get('/create', [CartController::class, 'create'])->name('tenant.cart.create'); // Optional - Add item page
    Route::post('/', [CartController::class, 'addItem'])->name('tenant.cart.store'); // Add to cart
    Route::get('/{id}/edit', [CartController::class, 'edit'])->name('tenant.cart.edit'); // Edit cart item
    Route::put('/{id}', [CartController::class, 'update'])->name('tenant.cart.update'); // Update cart item
    Route::delete('/{id}', [CartController::class, 'destroy'])->name('tenant.cart.destroy'); // Remove from cart
    Route::get('/checkout', [CartController::class, 'checkout'])->name('tenant.cart.checkout'); // Checkout page
});


        // Switch Customer
        Route::get('/switch-customer/{id}', [CustomerController::class, 'switchCustomer'])->name('customer.switch');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('tenant.orders.index');
        Route::get('orders/checkout', [OrderController::class, 'checkout'])->name('tenant.orders.checkout');
        Route::post('orders', [OrderController::class, 'placeOrder'])->name('tenant.orders.store');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('tenant.orders.show');

        // Shipping Methods
        Route::resource('shipping-methods', \App\Http\Controllers\Tenant\ShippingMethodController::class)->names([
            'index' => 'tenant.shipping-methods.index',
            'create' => 'tenant.shipping-methods.create',
            'store' => 'tenant.shipping-methods.store',
            'show' => 'tenant.shipping-methods.show',
            'edit' => 'tenant.shipping-methods.edit',
            'update' => 'tenant.shipping-methods.update',
            'destroy' => 'tenant.shipping-methods.destroy'
        ]);

        // Nested Rates
        Route::prefix('shipping-methods/{shipping_method}')->group(function () {
            Route::resource('rates', \App\Http\Controllers\Tenant\ShippingRateController::class)->names([
                'index' => 'tenant.shipping-rates.index',
                'create' => 'tenant.shipping-rates.create',
                'store' => 'tenant.shipping-rates.store',
                'show' => 'tenant.shipping-rates.show',
                'edit' => 'tenant.shipping-rates.edit',
                'update' => 'tenant.shipping-rates.update',
                'destroy' => 'tenant.shipping-rates.destroy'
            ]);
        });
    });
