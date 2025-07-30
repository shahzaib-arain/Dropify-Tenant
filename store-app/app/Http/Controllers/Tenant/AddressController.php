<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AddressController extends Controller
{
    use AuthorizesRequests;
    public function index(Customer $customer)
    {
        $this->authorize('view', $customer);
        $addresses = $customer->addresses;
        return view('tenant.addresses.index', compact('customer', 'addresses'));
    }

    public function create(Customer $customer)
    {
        $this->authorize('update', $customer);
        return view('tenant.addresses.create', compact('customer'));
    }

    public function store(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'type' => 'required|in:billing,shipping',
            'line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $validated['tenant_id'] = tenant('id');
        $customer->addresses()->create($validated);

        return redirect()->route('tenant.customers.show', [
                'subdomain' => tenant('subdomain'),
                'customer' => $customer->id
            ])
            ->with('success', 'Address added successfully.');
    }

    public function edit(Customer $customer, Address $address)
    {  
        $this->authorize('update', $customer);
        return view('tenant.addresses.edit', compact('customer', 'address'));
    }

    public function update(Request $request, Customer $customer, Address $address)
    {
        $this->authorize('update', $customer);

        $validated = $request->validate([
            'type' => 'required|in:billing,shipping',
            'line1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $address->update($validated);

        return redirect()->route('tenant.customers.show', [
                'subdomain' => tenant('subdomain'),
                'customer' => $customer->id
            ])
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(Customer $customer, Address $address)
    {
        $this->authorize('update', $customer);
        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }
}