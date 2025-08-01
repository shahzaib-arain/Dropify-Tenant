@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>My Products</h2>
    <a href="{{ tenant_route('tenant.products.create') }}" class="btn btn-success mb-3">
        Add Product
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
                <th>Add to Cart</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>${{ $product->price }}</td>
                <td>{{ $product->inventory->quantity ?? 0 }}</td>
                <td>
                      <!-- Edit Button -->
<a href="{{ tenant_route('tenant.products.edit', [
        'subdomain' => tenant()->subdomain,
        'id' => $product->id
    ]) }}"
   class="btn btn-warning btn-sm">
   Edit
</a>

   <!-- Delete Button -->
         <form method="POST" action="{{ tenant_route('tenant.products.destroy', [
        'subdomain' => tenant()->subdomain,
        'id' => $product->id
    ]) }}"
      style="display:inline-block"
      onsubmit="return confirm('Are you sure?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-danger">Delete</button>
</form>


                </td>
           <td>
    <form action="{{ tenant_route('tenant.cart.store', ['subdomain' => tenant()->subdomain]) }}" method="POST" class="d-flex">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="number" name="quantity" value="1" min="1"
               max="{{ $product->inventory->quantity }}"
               class="form-control form-control-sm me-2" style="width: 60px;">
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fas fa-cart-plus"></i> Add
        </button>
    </form>
</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
