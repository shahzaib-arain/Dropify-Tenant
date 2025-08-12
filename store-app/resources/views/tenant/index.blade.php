@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-truck me-1"></i>
            Shipping Rates for {{ $method->name }}
        </h1>
        @if(!$method->rate)
            <a href="{{ route('tenant.shipping-rates.create', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $method->id
            ]) }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Add Rates
            </a>
        @endif
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($method->rate)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Base Cost</th>
                            <th>Per Kg</th>
                            <th>Per cm³</th>
                            <th>Per Km</th>
                            <th>Flat Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${{ number_format($rate->base_cost, 2) }}</td>
                            <td>${{ number_format($rate->rate_per_kg, 2) }}</td>
                            <td>${{ number_format($rate->rate_per_cm3, 4) }}</td>
                            <td>${{ number_format($rate->rate_per_km, 2) }}</td>
                            <td>
                                @if($rate->flat_rate)
                                    <span class="badge bg-success">${{ number_format($rate->flat_rate, 2) }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tenant.shipping-rates.edit', [
                                        'subdomain' => tenant('subdomain'),
                                        'shipping_method' => $method->id,
                                        'rate' => $rate->id
                                    ]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No rate configuration found for this shipping method.
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('tenant.shipping-methods.index', ['subdomain' => tenant('subdomain')]) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Methods
                </a>
            </div>
        </div>
    </div>
</div>
@endsection