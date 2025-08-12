@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Rates for {{ $method->name }}</h2>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('tenant.shipping-rates.update', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $method->id,
                'rate' => $rate->id
            ]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Same form fields as create.blade.php but with old() values -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="base_cost" class="form-label">Base Cost ($)</label>
                        <input type="number" step="0.01" class="form-control" id="base_cost" 
                               name="base_cost" value="{{ old('base_cost', $rate->base_cost) }}" required min="0">
                    </div>
                    
                    <!-- Include all other fields similarly -->
                </div>
                
                <button type="submit" class="btn btn-primary">Update Rates</button>
                <a href="{{ route('tenant.shipping-methods.show', [
                    'subdomain' => tenant('subdomain'),
                    'shipping_method' => $method->id
                ]) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection