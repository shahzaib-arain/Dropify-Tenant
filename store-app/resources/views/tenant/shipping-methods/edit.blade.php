@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Shipping Method</h2>
    
    <div class="card">
        <div class="card-body">
           <form action="{{ tenant_route('tenant.shipping-methods.update', ['shipping_method' => $shipping_method->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Method Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $shipping_method->name) }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="mode" class="form-label">Transport Mode</label>
                    <select class="form-select" id="mode" name="mode" required>
                        <option value="air" @selected($shipping_method->mode === 'air')>Air</option>
                        <option value="sea" @selected($shipping_method->mode === 'sea')>Sea</option>
                        <option value="land" @selected($shipping_method->mode === 'land')>Land</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Method</button>
                <a href="{{ route('tenant.shipping-methods.show', [
                    'subdomain' => tenant('subdomain'),
                    'shipping_method' => $shipping_method->id
                ]) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection