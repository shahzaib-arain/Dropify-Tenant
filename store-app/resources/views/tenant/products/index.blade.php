@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>My Products</h2>
    <a href="{{ route('tenant.products.create', ['subdomain' => tenant()->subdomain]) }}" class="btn btn-success mb-3">
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
                    <a href="{{ route('tenant.products.edit', ['product' => $product->id, 'subdomain' => tenant()->subdomain]) }}"
                       class="btn btn-sm btn-warning">Edit</a>

                    <form method="POST"
                          action="{{ route('tenant.products.destroy', ['product' => $product->id, 'subdomain' => tenant()->subdomain]) }}"
                          style="display:inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
