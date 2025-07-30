<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

public function register(): void
{
 
}


    /**
     * Bootstrap any application services.
     */ 
    public function boot(): void
    {
         

    Route::bind('product', function ($value) {
        return \App\Models\Product::where('id', $value)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();
    });
    
    }
}
