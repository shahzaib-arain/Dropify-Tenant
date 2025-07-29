<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::with(['customer', 'items.product'])->latest()->paginate(25)
        ]);
    }
    
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        return view('orders.show', [
            'order' => $order->load(['items.product', 'billingAddress', 'shippingAddress'])
        ]);
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->status
        ]);
        
        return back()->with('success', 'Order status updated');
    }
}