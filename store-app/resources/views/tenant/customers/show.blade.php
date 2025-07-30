    @extends('layouts.app')

    @section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Customer Details</h2>
                    <div>
                        <a href="{{ route('tenant.addresses.create', ['subdomain' => tenant('subdomain'), 'customer' => $customer->id]) }}" 
                        class="btn btn-sm btn-light">
                            <i class="fas fa-plus"></i> Add Address
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">Customer Information</h5>
                        <p><strong>Name:</strong> {{ $customer->name }}</p>
                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('tenant.customers.edit', ['subdomain' => tenant('subdomain'), 'customer' => $customer->id]) }}" 
                        class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('tenant.customers.destroy', ['subdomain' => tenant('subdomain'), 'customer' => $customer->id]) }}" 
                            method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <h5 class="border-bottom pb-2 mb-3">Addresses</h5>
                @if($customer->addresses->isEmpty())
                    <div class="alert alert-info">No addresses found.</div>
                @else
                    <div class="row">
                        @foreach($customer->addresses as $address)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-capitalize">{{ $address->type }} Address</span>
                                        <div>
                                            <a href="{{ route('tenant.addresses.edit', [
                                                'subdomain' => tenant('subdomain'),
                                                'customer' => $customer->id,
                                                'address' => $address->id
                                            ]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1">{{ $address->line1 }}</p>
                                    <p class="mb-1">{{ $address->city }}, {{ $address->state }}</p>
                                    <p class="mb-1">{{ $address->country }} - {{ $address->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endsection