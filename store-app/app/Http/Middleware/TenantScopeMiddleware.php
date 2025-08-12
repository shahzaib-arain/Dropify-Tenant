<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Scopes\TenantScope;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;

class TenantScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Skip tenant resolution for admin domain and localhost
        if ($host === 'admin.example.test' || $host === 'localhost' || $host === '127.0.0.1') {
            return $next($request);
        }

        // Extract subdomain from host
        $subdomain = str_replace('.example.test', '', $host);
        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {  
            abort(404, 'Tenant not found');
        }

        // Set current tenant in application container and views
  app()->instance('currentTenant', $tenant);
View::share([
    'currentTenant' => $tenant,
    'tenantSubdomain' => $tenant->subdomain 
]);
        
        // Remove any existing global scopes
        Product::withoutGlobalScopes();
        Customer::withoutGlobalScopes();
        Order::withoutGlobalScopes();
        
        // Apply fresh tenant scopes
        Product::addGlobalScope(new TenantScope($tenant->id));
        Customer::addGlobalScope(new TenantScope($tenant->id));
        Order::addGlobalScope(new TenantScope($tenant->id));
        


        ShippingMethod::withoutGlobalScopes();
ShippingRate::withoutGlobalScopes();

ShippingMethod::addGlobalScope(new TenantScope($tenant->id));
ShippingRate::addGlobalScope(new TenantScope($tenant->id));
        // Verify user has access to this tenant
        if (Auth::check() && Auth::user()->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized tenant access.');
        }

        return $next($request);



    }
}   