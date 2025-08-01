<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index($subdomain)
    {
        $cart = $this->getOrCreateCart();
        
        if (!$cart) {
            abort(500, 'Unable to load your shopping cart');
        }

        $cart->load(['items.product.inventory']);

        return view('tenant.cart.index', [
            'cart' => $cart,
            'subtotal' => $this->calculateSubtotal($cart)
        ]);
    }

    public function addItem(Request $request, $subdomain)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::with('inventory')->findOrFail($validated['product_id']);

        if ($product->inventory->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }

        $cart = $this->getOrCreateCart();

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $validated['quantity'];

            if ($product->inventory->quantity < $newQuantity) {
                return back()->with('error', 'Not enough stock available for the additional quantity');
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price
            ]);
        }

        return redirect()->route('tenant.cart.index', ['subdomain' => $subdomain])
            ->with('success', 'Product added to cart');
    }

    public function update(Request $request, $subdomain, $id)
    {  
        Log::debug('Update method called', [
            'cart_item_id' => $id,
            'quantity' => $request->quantity
        ]);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::findOrFail($id);
        $product = $cartItem->product()->with('inventory')->first();

        if ($product->inventory->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }

        $cartItem->update([
            'quantity' => $validated['quantity']
        ]);

        return redirect()->route('tenant.cart.index', ['subdomain' => $subdomain])
            ->with('success', 'Cart updated');
    }

    public function destroy($subdomain, $id)
    {  

        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return redirect()->route('tenant.cart.index', ['subdomain' => $subdomain])
            ->with('success', 'Item removed from cart');
    }

    protected function getOrCreateCart()
    {
        $tenant = Auth::user()->tenant;
        $cart = Cart::firstOrCreate([
            'tenant_id' => $tenant->id,
            'customer_id' => Auth::id(),
            'status' => 'open'
        ]);

        return $cart->load('items');
    }

    protected function calculateSubtotal(Cart $cart)
    {
        return $cart->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    public function checkout($subdomain)
    {
        $customer = Auth::user()->customer ?? null;

        if (!$customer) {
            return redirect()->route('tenant.customers.create', [
                'subdomain' => $subdomain
            ])->with('error', 'Please create a customer profile first.');
        }

        $addresses = $customer->addresses()->get() ?? collect();
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('tenant.cart.index', [
                'subdomain' => $subdomain
            ])->with('error', 'Your cart is empty');
        }

        return view('tenant.orders.checkout', [
            'customer'   => $customer,
            'addresses'  => $addresses,
            'cart'       => $cart,
            'subdomain'  => $subdomain,
            'subtotal'   => $cart->items->sum(fn($item) => $item->quantity * $item->unit_price)
        ]);
    }
}
