<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index(ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        $rate = $shipping_method->rate;
        
        return view('tenant.shipping-rates.index', [
            'method' => $shipping_method,
            'rate' => $rate
        ]);
    }

    public function create(ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        return view('tenant.shipping-rates.create', ['method' => $shipping_method]);
    }

    public function store(Request $request, ShippingMethod $shipping_method)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);

        $validated = $request->validate([
            'base_cost' => 'required|numeric|min:0',
            'rate_per_kg' => 'nullable|numeric|min:0',
            'rate_per_cm3' => 'nullable|numeric|min:0',
            'rate_per_km' => 'nullable|numeric|min:0',
            'flat_rate' => 'nullable|numeric|min:0'
        ]);

        $shipping_method->rate()->create([
            'tenant_id' => tenant('id'),
            ...$validated
        ]);

        return redirect()->route('tenant.shipping-methods.show', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $shipping_method->id
            ])
            ->with('success', 'Shipping rate created');
    }

    public function edit(ShippingMethod $shipping_method, ShippingRate $rate)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        abort_unless($rate->shipping_method_id === $shipping_method->id, 404);

        return view('tenant.shipping-rates.edit', [
            'method' => $shipping_method,
            'rate' => $rate
        ]);
    }

    public function update(Request $request, ShippingMethod $shipping_method, ShippingRate $rate)
    {
        abort_unless($shipping_method->tenant_id === tenant('id'), 403);
        abort_unless($rate->shipping_method_id === $shipping_method->id, 404);

        $validated = $request->validate([
            'base_cost' => 'required|numeric|min:0',
            'rate_per_kg' => 'nullable|numeric|min:0',
            'rate_per_cm3' => 'nullable|numeric|min:0',
            'rate_per_km' => 'nullable|numeric|min:0',
            'flat_rate' => 'nullable|numeric|min:0'
        ]);

        $rate->update($validated);

        return redirect()->route('tenant.shipping-methods.show', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $shipping_method->id
            ])
            ->with('success', 'Shipping rate updated');
    }
}