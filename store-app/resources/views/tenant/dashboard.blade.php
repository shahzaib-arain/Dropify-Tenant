@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="h2 mb-4">{{ $tenant->name }} Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <h2>{{ $stats['products'] ?? 0 }}</h2>
                    @if(isset($tenant) && $tenant)
                        {{-- Temporarily comment out or remove the link --}}
                        {{-- <a href="{{ route('tenant.products.index', ['subdomain' => $tenant->subdomain]) }}" class="text-white small">View all</a> --}}
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <h2>{{ $stats['customers'] ?? 0 }}</h2>
                    {{-- Similar removal for customers --}}
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <h2>{{ $stats['orders'] ?? 0 }}</h2>
                    {{-- Similar removal for orders --}}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Recent Activity
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <i class="fas fa-user-plus text-success me-2"></i> New customer registered
                    <span class="float-end text-muted small">2 hours ago</span>
                </li>
                <li class="list-group-item">
                    <i class="fas fa-shopping-cart text-primary me-2"></i> New order received
                    <span class="float-end text-muted small">5 hours ago</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection