<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\OrderController;
use Illuminate\Support\Facades\Route;

Route::domain('admin.example.test')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('superadmin.dashboard');
        Route::resource('tenants', TenantController::class)->except(['index']);
    });

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::domain('{subdomain}.example.test')
    ->middleware([
        'auth',
        'role:tenantadmin',
        \App\Http\Middleware\TenantScopeMiddleware::class
    ])
    ->group(function () {
        // Dashboard routes
        Route::get('/', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
        
        // Add these new resource routes
    Route::resource('products', \App\Http\Controllers\Tenant\ProductController::class)->names([
    'index' => 'tenant.products.index',
    'create' => 'tenant.products.create',
    'store' => 'tenant.products.store',
    'show' => 'tenant.products.show',
    'edit' => 'tenant.products.edit',
    'update' => 'tenant.products.update',
    'destroy' => 'tenant.products.destroy'
]);

        
        // Route::resource('customers', \App\Http\Controllers\Tenant\CustomerController::class)->names([
        //     'index' => 'tenant.customers.index',
        //     // ... other route names
        // ]);
        
        // Route::resource('orders', \App\Http\Controllers\Tenant\OrderController::class)->names([
        //     'index' => 'tenant.orders.index',
        //     // ... other route names
        // ]);
    });