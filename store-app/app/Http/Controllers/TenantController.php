<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tenants = Tenant::with(['user.role'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('subdomain', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('superadmin.dashboard', compact('tenants', 'search'));
    }

    public function create()
    {
        return view('superadmin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $tenant = Tenant::create([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
        ]);

        $tenantAdminRole = Role::where('name', 'tenantadmin')->firstOrFail();
        
        $user = User::create([
            'name' => $request->name . ' Admin',
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'tenant_id' => $tenant->id,
            'role_id' => $tenantAdminRole->id,
        ]);

        $tenant->update(['user_id' => $user->id]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Tenant and admin user created successfully!');
    }

    public function edit(Tenant $tenant)
    {
        return view('superadmin.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|unique:tenants,subdomain,'.$tenant->id,
        ]);

        $tenant->update($request->only(['name', 'subdomain']));

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Tenant updated successfully!');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Tenant deleted successfully!');
    }

   public function dashboard()
{
    $tenant = app('currentTenant');
    $user = Auth::user();
    
    $stats = [
        'products' => \App\Models\Product::count(),
        'customers' => \App\Models\Customer::count(),
        'orders' => \App\Models\Order::count(),
    ];

    return view('tenant.dashboard', [
        'tenant' => $tenant,
        'user' => $user,
        'stats' => $stats,
        'subdomain' => $tenant->subdomain // Add this
    ]);
}
}