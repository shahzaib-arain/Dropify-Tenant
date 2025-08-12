<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $tenantId = tenant()?->id;

        $methods = ShippingMethod::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(10);

        Log::debug('Shipping methods retrieved', [
            'count' => $methods->count(),
            'tenant_id' => $tenantId,
            'first_method' => $methods->first()?->toArray(),
        ]);

        return view('tenant.shipping-methods.index', compact('methods'));
    }

    public function create()
    {
        return view('tenant.shipping-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mode' => 'required|in:air,sea,land',
        ]);

        $tenantId = tenant()?->id;

        Log::debug('Creating shipping method', [
            'tenant_id' => $tenantId,
            'data' => $validated,
        ]);

        $method = ShippingMethod::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'mode' => $validated['mode'],
        ]);

        Log::debug('Created shipping method', ['method' => $method]);

     return redirect()
    ->route('tenant.shipping-methods.index', ['subdomain' => request()->route('subdomain')])
    ->with('success', 'Shipping method created');

    }

    public function show($subdomain, $id)
    {
        $method = ShippingMethod::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->with('rate') // Optional: remove if not needed
            ->firstOrFail();

        return view('tenant.shipping-methods.show', compact('method'));
    }

    public function edit($subdomain, $id)
    {
        $method = ShippingMethod::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        return view('tenant.shipping-methods.edit', compact('method'));
    }

    public function update(Request $request, $subdomain, $id)
    {
        $method = ShippingMethod::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mode' => 'required|in:air,sea,land',
        ]);

        $method->update($validated);

        return redirect()
            ->route('tenant.shipping-methods.index', ['subdomain' => $subdomain])
            ->with('success', 'Shipping method updated');
    }

    public function destroy($subdomain, $id)
    {
        $method = ShippingMethod::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        $method->delete();

        return redirect()
            ->route('tenant.shipping-methods.index', ['subdomain' => $subdomain])
            ->with('success', 'Shipping method deleted');
    }
}
