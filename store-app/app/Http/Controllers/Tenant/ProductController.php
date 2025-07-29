<?php


namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('inventory')
            ->where('tenant_id', tenant()->id)
            ->paginate(25);

        return view('tenant.products.index', compact('products'));
    }

    public function create()
    {
        return view('tenant.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,NULL,id,tenant_id,' . tenant()->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'quantity' => 'required|integer|min:0'
        ]);

        $product = Product::create([
            'tenant_id' => tenant()->id,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'length' => $validated['length'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
        ]);

        $product->inventory()->create([
            'quantity' => $validated['quantity'],
            'tenant_id' => tenant()->id,
        ]);

        return redirect()->route('tenant.products.index', ['subdomain' => tenant()->subdomain])
            ->with('success', 'Product created successfully');
    }
    public function edit(Product $product)
{
    abort_unless($product->tenant_id === tenant()->id, 403);
    return view('tenant.products.edit', compact('product'));
}

public function update(Request $request, Product $product)
{
    abort_unless($product->tenant_id === tenant()->id, 403);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku,' . $product->id . ',id,tenant_id,' . tenant()->id,
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'length' => 'nullable|numeric',
        'width' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'weight' => 'nullable|numeric',
    ]);

    $product->update($validated);

    return redirect()->route('tenant.products.index', ['subdomain' => tenant()->subdomain])
        ->with('success', 'Product updated successfully');
}

public function destroy(Product $product)
{
    abort_unless($product->tenant_id === tenant()->id, 403);

    $product->inventory()->delete();
    $product->delete();

    return redirect()->route('tenant.products.index', ['subdomain' => tenant()->subdomain])
        ->with('success', 'Product deleted successfully');
}

}
