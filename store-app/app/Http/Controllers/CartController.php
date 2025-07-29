<?php
// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;
use Illuminate\Http\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class CartController extends Controller

{
    public function show()
    {
        $cart = Cart::firstOrCreate([
            'customer_id' =>  Auth::id(),
            'status' => 'open'
        ]);
        
        return view('cart.show', [
            'cart' => $cart->load('items.product')
        ]);
    }
    
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = Cart::firstOrCreate([
            'customer_id' =>  Auth::id(),
            'status' => 'open'
        ]);
        
        $cart->items()->updateOrCreate(
            ['product_id' => $validated['product_id']],
            ['quantity' => $validated['quantity']]
        );
        
        return back()->with('success', 'Item added to cart');
    }
    
    // Add more cart methods
}