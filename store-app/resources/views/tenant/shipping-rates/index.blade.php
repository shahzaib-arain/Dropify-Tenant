@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Shipping Rate for: {{ $method->name }}</h2>

    @if($rate)
        <div class="card">
            <div class="card-body">
                <p><strong>Base Cost:</strong> ${{ $rate->base_cost }}</p>
                <p><strong>Rate per Kg:</strong> ${{ $rate->rate_per_kg }}</p>
                <p><strong>Rate per cm³:</strong> ${{ $rate->rate_per_cm3 }}</p>
                <p><strong>Rate per Km:</strong> ${{ $rate->rate_per_km }}</p>
                <p><strong>Flat Rate:</strong> ${{ $rate->flat_rate }}</p>
                <a href="{{ tenant_route('tenant.shipping-rates.edit', ['shipping_method' => $method->id, 'rate' => $rate->id, 'subdomain' => tenant()->subdomain]) }}" class="btn btn-warning">
                    Edit Rate
                </a>
            </div>
        </div>
    @else
        <p>No rate set for this method yet.</p>
        <a href="{{ tenant_route('tenant.shipping-rates.create', ['shipping_method' => $method->id, 'subdomain' => tenant()->subdomain]) }}" class="btn btn-success">
            Create Rate
        </a>
    @endif
</div>
@endsection
