<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'products' => Product::with('inventory')->paginate(25)
        ]);
    }
    
    public function store(Request $request)
{   
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|unique:products,sku',
        'price' => 'required|numeric|min:0',
        // ... other fields
    ]);

    // Include tenant_id
    $product = Product::create([
        ...$validated,
        'tenant_id' => tenant()->id,
    ]);

    // Create inventory record
    $product->inventory()->create([
        'quantity' => $request->input('quantity', 0)
    ]);

    return redirect()->route('products.index');
}

    // Add show(), edit(), update(), destroy() methods
}