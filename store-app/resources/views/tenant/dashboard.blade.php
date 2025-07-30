@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="h2 mb-4">{{ $tenant->name }} Dashboard</h1>
    
    <div class="row mb-4">
        <!-- Existing Product Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <h2>{{ $stats['products'] ?? 0 }}</h2>
                    <a href="{{ route('tenant.products.index', ['subdomain' => $tenant->subdomain]) }}" 
                       class="text-white small">View all</a>
                </div>
            </div>
        </div>
        
        <!-- Existing Customer Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <h2>{{ $stats['customers'] ?? 0 }}</h2>
                    <a href="{{ route('tenant.customers.index', ['subdomain' => $tenant->subdomain]) }}" 
                       class="text-white small">View all</a>
                </div>
            </div>
        </div>
        
        <!-- New Orders Card -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Orders</h5>
                    <h2>{{ $stats['orders'] ?? 0 }}</h2>
                    <a href="{{ route('tenant.orders.index', ['subdomain' => $tenant->subdomain]) }}" 
                       class="text-white small">View all</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Recent Orders
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($stats['recentOrders'] as $order)
                            <a href="{{ route('tenant.orders.show', ['subdomain' => $tenant->subdomain, 'order' => $order->id]) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <strong>Order #{{ $order->id }}</strong>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} ms-2">
                                            {{ strtoupper($order->payment_status) }}
                                        </span>
                                    </span>
                                    <span>${{ number_format($order->total_amount + $order->shipping_cost, 2) }}</span>
                                </div>
                                <div class="text-muted small">
                                    {{ $order->customer->name }} • {{ $order->created_at->diffForHumans() }}
                                </div>
                            </a>
                        @empty
                            <div class="list-group-item text-muted">
                                No recent orders
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Keep your existing recent activity if needed -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Recent Activity
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <!-- Your existing activity items -->
                        <li class="list-group-item">
                            <i class="fas fa-user-plus text-success me-2"></i> New customer registered
                            <span class="float-end text-muted small">2 hours ago</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection