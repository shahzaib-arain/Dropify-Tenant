@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->id }}</h2>
        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
            {{ strtoupper($order->payment_status) }}
        </span>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Billing Address
                </div>
                <div class="card-body">
                    <address>
                        <strong>{{ $order->billingAddress->type }} address</strong><br>
                        {{ $order->billingAddress->line1 }}<br>
                        {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}<br>
                        {{ $order->billingAddress->country }} {{ $order->billingAddress->postal_code }}
                    </address>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Shipping Address
                </div>
                <div class="card-body">
                    <address>
                        <strong>{{ $order->shippingAddress->type }} address</strong><br>
                        {{ $order->shippingAddress->line1 }}<br>
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                        {{ $order->shippingAddress->country }} {{ $order->shippingAddress->postal_code }}
                    </address>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            Order Items
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Shipping:</td>
                        <td>${{ number_format($order->shipping_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td>${{ number_format($order->total_amount + $order->shipping_cost, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('tenant.dashboard', ['subdomain' => tenant('subdomain')]) }}" class="btn btn-primary">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection