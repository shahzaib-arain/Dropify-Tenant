<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('tenant')) {
    function tenant()
    {
        return app('currentTenant');
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return Auth::check() && Auth::user()->role->name === 'superadmin';
    }
}

if (!function_exists('isTenantAdmin')) {
    function isTenantAdmin()
    {
        return Auth::check() && Auth::user()->role->name === 'tenantadmin';
    }
}