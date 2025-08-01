<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
       View::composer('*', function ($view) {
        $cartCount = 0;
        $customer = null;
        $allCustomers = collect();
        
        if (Auth::check() && isTenantAdmin()) {
            $customerId = session('active_customer_id');
            $customer = $customerId ? \App\Models\Customer::find($customerId) : null;
            
            $cartCount = ($customer && method_exists($customer, 'carts'))
                ? optional($customer->carts()->open()->withCount('items')->first())->items_count
                : 0;
                
            $allCustomers = tenant() 
                ? \App\Models\Customer::where('tenant_id', tenant('id'))->get() 
                : collect();
        }
        
        $view->with([
            'cartCount' => $cartCount,
            'customer' => $customer,
            'allCustomers' => $allCustomers
        ]);
    });
    }
}
