<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Product;
use App\Scopes\TenantScope;

class TenantScopeMiddleware
{
// app/Http/Middleware/TenantScopeMiddleware.php
public function handle(Request $request, Closure $next)
{
    $host = $request->getHost();
    
    // Skip tenant resolution for admin domain
    if ($host === 'admin.example.test' || $host === 'localhost' || $host === '127.0.0.1') {
        return $next($request);
    }

    $subdomain = str_replace('.example.test', '', $host);
    $tenant = Tenant::where('subdomain', $subdomain)->first();

    if (!$tenant) {
        abort(404, 'Tenant not found');
    }

    app()->instance('currentTenant', $tenant);
    View::share('currentTenant', $tenant);
    
    // Apply tenant scope to relevant models
    \App\Models\Product::addGlobalScope(new \App\Scopes\TenantScope($tenant->id));
    \App\Models\Customer::addGlobalScope(new \App\Scopes\TenantScope($tenant->id));
    \App\Models\Order::addGlobalScope(new \App\Scopes\TenantScope($tenant->id));

    if (Auth::check() && Auth::user()->tenant_id !== $tenant->id) {
        abort(403, 'Unauthorized tenant access.');
    }

    return $next($request);
}
}
