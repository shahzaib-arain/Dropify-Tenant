<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

if (!function_exists('tenant')) {
    /**
     * Get the current tenant instance.
     *
     * @return \App\Models\Tenant|null
     */
    function tenant()
    {
        return app()->bound('currentTenant') ? app('currentTenant') : null;
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if the authenticated user is a superadmin.
     *
     * @return bool
     */
    function isSuperAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return optional($user->role)->name === 'superadmin';
    }
}

if (!function_exists('isTenantAdmin')) {
    /**
     * Check if the authenticated user is a tenantadmin.
     *
     * @return bool
     */
    function isTenantAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return optional($user->role)->name === 'tenantadmin';
    }
}
if (!function_exists('tenant_route')) {
function tenant_route($name, $parameters = [], $absolute = true)
{
    $tenant = tenant();

    if (!$tenant || !$tenant->subdomain) {
        report(new \Exception("Tenant route generation failed: No tenant/subdomain available for route {$name}"));
        return route('tenant.dashboard'); // fallback
    }

    $domain = $tenant->subdomain . '.example.test';
    $port = app()->environment('local') ? ':8000' : '';

    try {
        $route = app('router')->getRoutes()->getByName($name);
        if (!$route) {
            throw new \Exception("Route [{$name}] not found");
        }

        $path = $route->uri();

        // Always make sure parameters is an array
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        // Get parameter names from the route definition
        preg_match_all('/\{([^}]+)\}/', $path, $matches);
        $paramNames = $matches[1];

        // Always include subdomain if it's a route parameter
        if (in_array('subdomain', $paramNames) && !isset($parameters['subdomain'])) {
            $parameters = ['subdomain' => $tenant->subdomain] + $parameters;
        }

        // If parameters are numeric (0,1,2...), map them to param names
        if (!empty($paramNames) && array_keys($parameters) === range(0, count($parameters) - 1)) {
            $parameters = array_combine($paramNames, $parameters);
        }

        // Replace route parameters in path
        foreach ($parameters as $key => $value) {
            $path = str_replace("{{$key}}", $value, $path);
        }

        // Remove optional params if not provided
        $path = preg_replace('/\{[^}]+\?\}/', '', $path);

        // Clean up slashes
        $path = str_replace('//', '/', $path);
        $path = ltrim($path, '/');

        return "http://{$domain}{$port}/{$path}";
    } catch (\Exception $e) {
        report($e);
        return "http://{$domain}{$port}/dashboard";
    }
}




}
 if (!function_exists('dashboard_url')) {
    function dashboard_url() {
        if (isSuperAdmin()) {
            return route('superadmin.dashboard');
        }

        $user = Auth::user();
        if ($user && $user->tenant && $user->tenant->subdomain) {
            return url('http://' . $user->tenant->subdomain . '.example.test:8000/dashboard');
        }

        return url('/dashboard');
    }
}