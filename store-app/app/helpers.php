<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User; // Add this import
use Illuminate\Database\Eloquent\Builder; // Add this import

if (!function_exists('tenant')) {
    /**
     * Get the current tenant instance
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
     * Check if user is superadmin
     *
     * @return bool
     */
    function isSuperAdmin()
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && $user->role && $user->role->name === 'superadmin';
    }
}

if (!function_exists('isTenantAdmin')) {
    /**
     * Check if user is tenantadmin
     *
     * @return bool
     */
    function isTenantAdmin()
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && $user->role && $user->role->name === 'tenantadmin';
    }
}