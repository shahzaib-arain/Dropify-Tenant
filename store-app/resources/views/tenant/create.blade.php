@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Configure Rates for {{ $method->name }}</h2>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('tenant.shipping-rates.store', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $method->id
            ]) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="base_cost" class="form-label">Base Cost ($)</label>
                        <input type="number" step="0.01" class="form-control" id="base_cost" 
                               name="base_cost" required min="0">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="flat_rate" class="form-label">Flat Rate ($)</label>
                        <input type="number" step="0.01" class="form-control" id="flat_rate" 
                               name="flat_rate" min="0">
                        <small class="text-muted">Optional - overrides other rates if set</small>
                    </div>
                </div>
                
                <h5 class="mt-4">Variable Rates</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="rate_per_kg" class="form-label">Per Kg ($)</label>
                        <input type="number" step="0.01" class="form-control" id="rate_per_kg" 
                               name="rate_per_kg" min="0">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="rate_per_cm3" class="form-label">Per cm³ ($)</label>
                        <input type="number" step="0.0001" class="form-control" id="rate_per_cm3" 
                               name="rate_per_cm3" min="0">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="rate_per_km" class="form-label">Per Km ($)</label>
                        <input type="number" step="0.01" class="form-control" id="rate_per_km" 
                               name="rate_per_km" min="0">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Rates</button>
                <a href="{{ route('tenant.shipping-methods.show', [
                    'subdomain' => tenant('subdomain'),
                    'shipping_method' => $method->id
                ]) }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection