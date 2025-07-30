@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Your Shopping Cart</h4>
                </div>
                
                <div class="card-body">
                    @if($cart->items->isEmpty())
                        <div class="alert alert-info">Your cart is empty</div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <!-- Product image placeholder -->
                                                        <div class="bg-light" style="width: 60px; height: 60px;"></div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('tenant.cart.update', ['subdomain' => tenant('subdomain'), 'cart_item' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 60px;" class="form-control form-control-sm">
                                                </form>
                                            </td>
                                            <td>${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                            <td>
                                                <form action="{{ route('tenant.cart.destroy', ['subdomain' => tenant('subdomain'), 'cart_item' => $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Estimated Total:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    @if(!$cart->items->isEmpty())
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('tenant.orders.checkout', ['subdomain' => tenant('subdomain')]) }}" 
                               class="btn btn-primary btn-lg">
                                Proceed to Checkout
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    