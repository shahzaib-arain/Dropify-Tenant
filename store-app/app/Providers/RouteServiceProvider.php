<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        parent::boot();
        
        Route::bind('product', function ($value) {
            return Product::where('id', $value)
                ->where('tenant_id', tenant()?->id)
                ->firstOrFail();
        });

        Route::bind('customer', function ($value) {
            return Customer::where('id', $value)
                ->where('tenant_id', tenant()?->id)
                ->firstOrFail();
        });

Route::bind('cart_item', function ($value) {
    Log::debug('Binding cart_item', [
        'value' => $value,
        'tenant_id' => tenant()?->id,
        'auth_id' => Auth::id()
    ]);
    
    $item = \App\Models\CartItem::where('id', $value)
        ->whereHas('cart', function ($query) {
            $query->where('tenant_id', tenant()?->id)
                  ->where('customer_id', Auth::id());
        })
        ->firstOrFail();
        
    Log::debug('Found cart_item', ['item' => $item]);
    return $item;
});


     // In RouteServiceProvider.php
Route::bind('shipping_method', function ($value) {
    $query = \App\Models\ShippingMethod::where('id', $value);
    
    if ($tenantId = tenant('id')) {
        $query->where('tenant_id', $tenantId);
    }
    
    return $query->firstOrFail();
});
    }
}