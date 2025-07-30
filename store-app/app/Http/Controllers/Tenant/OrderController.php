<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    
public function index()
{
    $orders = Order::with('customer')
        ->where('tenant_id', tenant('id'))
        ->paginate(10); // ✅ use pagination instead of get()

    return view('tenant.orders.index', compact('orders'));
}


    public function checkout()
    {
        $cart = Cart::with(['items.product'])
            ->where('customer_id', Auth::id())
            ->where('status', Cart::STATUS_OPEN)
            ->firstOrFail();

        if ($cart->isEmpty()) {
            return redirect()->route('tenant.cart.index', ['subdomain' => tenant('subdomain')])
                ->with('error', 'Your cart is empty');
        }

        $customer = Auth::user();
        $addresses = $customer->addresses;

        return view('tenant.orders.checkout', [
            'cart' => $cart,
            'addresses' => $addresses,
            'subtotal' => $cart->subtotal
        ]);
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'use_same_address' => 'sometimes|boolean'
        ]);

        // Handle same address for billing/shipping
        if ($request->has('use_same_address')) {
            $validated['shipping_address_id'] = $validated['billing_address_id'];
        }

        $cart = Cart::with(['items.product.inventory'])
            ->where('customer_id', Auth::id())
            ->where('status', Cart::STATUS_OPEN)
            ->firstOrFail();

        // Verify addresses belong to customer
        $this->verifyAddresses($validated['billing_address_id'], $validated['shipping_address_id']);

        // Create order
        $order = $this->createOrderFromCart($cart, $validated);

        // Update cart status
        $cart->update(['status' => Cart::STATUS_CONVERTED]);

        return redirect()->route('tenant.orders.show', [
                'subdomain' => tenant('subdomain'),
                'order' => $order->id
            ])
            ->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        // Authorization check
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'billingAddress', 'shippingAddress']);

        return view('tenant.orders.show', compact('order'));
    }

    protected function verifyAddresses($billingId, $shippingId)
    {
        $customerId = Auth::id();
        
        $billingAddress = Address::where('id', $billingId)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        $shippingAddress = Address::where('id', $shippingId)
            ->where('customer_id', $customerId)
            ->firstOrFail();
    }

    protected function createOrderFromCart(Cart $cart, array $addressData)
    {
        // Start transaction
        return DB::transaction(function () use ($cart, $addressData) {
            // Create order
            $order = Order::create([
                'tenant_id' => tenant('id'),
                'customer_id' => $cart->customer_id,
                'billing_address_id' => $addressData['billing_address_id'],
                'shipping_address_id' => $addressData['shipping_address_id'],
                'total_amount' => $cart->subtotal,
                'shipping_cost' => 0, // Will be updated in shipping module
                'payment_status' => 'pending'
            ]);

            // Add order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price
                ]);

                // Update inventory (optional - can be done after payment)
                $item->product->inventory->decrement('quantity', $item->quantity);
            }

            return $order;
        });
        
    }
}