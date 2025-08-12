<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $customers = Customer::where('tenant_id', tenant()?->id)->latest()->paginate(10);
        return view('tenant.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('tenant.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('customers')->where(fn($query) => $query->where('tenant_id', tenant()?->id)),
            ],
        ]);

        $validated['tenant_id'] = tenant()?->id;

        Customer::create($validated);

        return redirect()->route('tenant.customers.index', ['subdomain' => tenant()->subdomain])
            ->with('success', 'Customer created successfully.');
    }

    public function show($subdomain, $id)
    {
        $customer = Customer::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->with('addresses')
            ->firstOrFail();

        $this->authorize('view', $customer);

        return view('tenant.customers.show', compact('customer'));
    }

    public function edit($subdomain, $id)
    {  


        $customer = Customer::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        return view('tenant.customers.edit', compact('customer'));
    }

    public function update(Request $request, $subdomain, $id)
    {
        $customer = Customer::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        $this->authorize('update', $customer);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . ',id,tenant_id,' . tenant()?->id,
        ]);

        $customer->update($validated);

        return redirect()->route('tenant.customers.index', ['subdomain' => tenant()->subdomain])
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($subdomain, $id)
    {
        $customer = Customer::where('id', $id)
            ->where('tenant_id', tenant()?->id)
            ->firstOrFail();

        $this->authorize('delete', $customer);

        $customer->delete();

        return redirect()->route('tenant.customers.index', ['subdomain' => tenant()->subdomain])
            ->with('success', 'Customer deleted successfully.');
    }

    public function switchCustomer($id)
    {
        $customer = Customer::where('tenant_id', tenant('id'))
            ->where('id', $id)
            ->firstOrFail();

        session(['active_customer_id' => $customer->id]);

        return redirect()->back()->with('success', 'Switched to customer: ' . $customer->name);
    }
}
