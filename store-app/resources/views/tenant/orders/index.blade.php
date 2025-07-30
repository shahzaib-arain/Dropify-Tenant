@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Orders</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer->name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-end">${{ number_format($order->total_amount + $order->shipping_cost, 2) }}</td>
                                <td>
                                    <a href="{{ route('tenant.orders.show', ['subdomain' => tenant('subdomain'), 'order' => $order->id]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($orders->hasPages())
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
@endif

        </div>
    </div>
</div>
@endsection