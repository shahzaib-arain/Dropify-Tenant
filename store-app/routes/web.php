<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\OrderController;
use App\Http\Controllers\Tenant\CartController;
use App\Http\Controllers\Tenant\AddressController;
use Illuminate\Support\Facades\Route;

// Super Admin Routes (admin.example.test)
Route::domain('admin.example.test')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('tenants', TenantController::class)->except(['index']);
    });

// Public Routes
Route::get('/', function () {
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

        // Products
        Route::resource('products', ProductController::class)->names([
            'index' => 'tenant.products.index',
            'create' => 'tenant.products.create',
            'store' => 'tenant.products.store',
            'show' => 'tenant.products.show',
            'edit' => 'tenant.products.edit',
            'update' => 'tenant.products.update',
            'destroy' => 'tenant.products.destroy'
        ]);

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
        Route::prefix('cart')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('tenant.cart.index');
            Route::post('/add-item', [CartController::class, 'addItem'])->name('tenant.cart.addItem');
            Route::put('/{cart_item}', [CartController::class, 'update'])->whereNumber('cart_item')->name('tenant.cart.update');
            Route::delete('/{cart_item}', [CartController::class, 'destroy'])->whereNumber('cart_item')->name('tenant.cart.destroy');
            Route::get('/checkout', [CartController::class, 'checkout'])->name('tenant.cart.checkout');
        });

        // Switch Customer
        Route::get('/switch-customer/{id}', [CustomerController::class, 'switchCustomer'])->name('customer.switch');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('tenant.orders.index');
        Route::get('orders/checkout', [OrderController::class, 'checkout'])->name('tenant.orders.checkout');
        Route::post('orders', [OrderController::class, 'placeOrder'])->name('tenant.orders.store');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('tenant.orders.show');
    });
