<?php
// app/Http/Controllers/CheckoutController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;
class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cart = Cart::with('items.product')
            ->where('customer_id',  Auth::id(),)
            ->where('status', 'open')
            ->firstOrFail();
            
        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        
        // Create order
        $order = Order::create([
            'customer_id' =>  Auth::id(),
            'total_amount' => $subtotal,
            'status' => 'pending'
            // ... other fields
        ]);
        
        // Add order items
        foreach($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->product->price
            ]);
        }
        
        // Process payment (example with Stripe)
        $stripe = new StripeClient(config('services.stripe.secret'));
        
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $subtotal * 100,
            'currency' => 'usd',
            'metadata' => ['order_id' => $order->id]
        ]);
        
        // Mark cart as converted
        $cart->update(['status' => 'converted']);
        
        return view('checkout.payment', [
            'clientSecret' => $paymentIntent->client_secret,
            'order' => $order
        ]);
    }
}