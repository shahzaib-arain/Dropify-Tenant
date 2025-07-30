<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Customer;

class RouteServiceProvider extends ServiceProvider
{
   
    public const HOME = 'tenant.dashboard'; 

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
    return \App\Models\CartItem::where('id', $value)
        ->whereHas('cart', function ($query) {
            $query->where('tenant_id', tenant()?->id)
                  ->where('customer_id', Auth::id());
        })
        ->firstOrFail();
});

    }
}
