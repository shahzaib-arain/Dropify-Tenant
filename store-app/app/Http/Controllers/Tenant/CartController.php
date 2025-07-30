<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.inventory']);
        
        return view('tenant.cart.index', [
            'cart' => $cart,
            'subtotal' => $this->calculateSubtotal($cart)
        ]);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = Product::with('inventory')->findOrFail($validated['product_id']);
        
        // Check inventory
        if ($product->inventory->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }
        
        $cart = $this->getOrCreateCart();
        
        // Check if item already exists
        $existingItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $validated['quantity'];
            
            // Check inventory again for updated quantity
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
        
        return redirect()->route('tenant.cart.index', ['subdomain' => tenant('subdomain')])
            ->with('success', 'Product added to cart');
    }

    public function update(Request $request, CartItem $cart_item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $product = $cart_item->product()->with('inventory')->first();
        
        // Check inventory
        if ($product->inventory->quantity < $validated['quantity']) {
            return back()->with('error', 'Not enough stock available');
        }
        
        $cart_item->update([
            'quantity' => $validated['quantity']
        ]);
        
        return redirect()->route('tenant.cart.index', ['subdomain' => tenant('subdomain')])
            ->with('success', 'Cart updated');
    }

    public function destroy(CartItem $cart_item)
    {
        $cart_item->delete();
        
        return redirect()->route('tenant.cart.index', ['subdomain' => tenant('subdomain')])
            ->with('success', 'Item removed from cart');
    }

    protected function getOrCreateCart()
    {
        return Cart::firstOrCreate([
            'tenant_id' => tenant('id'),
            'customer_id' => Auth::id(),
            'status' => 'open'
        ]);
    }
    
    protected function calculateSubtotal(Cart $cart)
    {
        return $cart->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}