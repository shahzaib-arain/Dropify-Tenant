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
    $methods = ShippingMethod::where('tenant_id', tenant('id'))
        ->latest()
        ->paginate(10);

    // Debug output
    Log::debug('Shipping methods retrieved', [
        'count' => $methods->count(),
        'tenant_id' => tenant('id'),
        'first_method' => $methods->first() ? $methods->first()->toArray() : null
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
        'mode' => 'required|in:air,sea,land'
    ]);

    // Debug before creation
    Log::debug('Creating shipping method', [
        'tenant_id' => tenant('id'),
        'data' => $validated
    ]);

    $method = ShippingMethod::create([
        'tenant_id' => tenant('id'), // Ensure this is set
        'name' => $validated['name'],
        'mode' => $validated['mode']
    ]);

    // Debug after creation
    Log::debug('Created shipping method', ['method' => $method]);

    return redirect()->route('tenant.shipping-methods.index', ['subdomain' => tenant('subdomain')])
        ->with('success', 'Shipping method created');
}

    public function show(ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        return view('tenant.shipping-methods.show', compact('shipping_method'));
    }

    public function edit(ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        return view('tenant.shipping-methods.edit', compact('shipping_method'));
    }

    public function update(Request $request, ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mode' => 'required|in:air,sea,land'
        ]);

        $shipping_method->update($validated);

        return redirect()->route('tenant.shipping-methods.index', ['subdomain' => tenant('subdomain')])
            ->with('success', 'Shipping method updated');
    }

    public function destroy(ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        $shipping_method->delete();

        return redirect()->route('tenant.shipping-methods.index', ['subdomain' => tenant('subdomain')])
            ->with('success', 'Shipping method deleted');
    }
}