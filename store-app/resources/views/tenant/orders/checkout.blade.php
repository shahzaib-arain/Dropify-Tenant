@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    Shipping Information
                </div>
                <div class="card-body">
                    <form action="{{ route('tenant.orders.store', ['subdomain' => tenant('subdomain')]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="use_same_address" name="use_same_address">
                                <label class="form-check-label" for="use_same_address">
                                    Use same address for billing and shipping
                                </label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_address_id" class="form-label">Billing Address</label>
                                <select class="form-select" id="billing_address_id" name="billing_address_id" required>
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->id }}">
                                            {{ $address->line1 }}, {{ $address->city }} ({{ $address->type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="shipping_address_id" class="form-label">Shipping Address</label>
                                <select class="form-select" id="shipping_address_id" name="shipping_address_id" required>
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->id }}">
                                            {{ $address->line1 }}, {{ $address->city }} ({{ $address->type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Order Summary
                </div>
                <div class="card-body">
                    <h5 class="card-title">Items ({{ $cart->items->count() }})</h5>
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($cart->items as $item)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                <span>${{ number_format($item->quantity * $item->unit_price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Shipping:</span>
                        <span>$0.00</span> <!-- Will be updated in shipping module -->
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('use_same_address').addEventListener('change', function() {
    const shippingSelect = document.getElementById('shipping_address_id');
    shippingSelect.disabled = this.checked;
    if (this.checked) {
        shippingSelect.value = document.getElementById('billing_address_id').value;
    }
});
</script>
@endsection