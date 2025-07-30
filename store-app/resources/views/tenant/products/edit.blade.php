@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Product</h2>
    <form method="POST" action="{{ route('tenant.products.update', ['product' => $product, 'subdomain' => tenant()->subdomain]) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3"><label>Price</label><input type="number" name="price" value="{{ $product->price }}" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label>Length</label><input type="number" name="length" value="{{ $product->length }}" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Width</label><input type="number" name="width" value="{{ $product->width }}" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Height</label><input type="number" name="height" value="{{ $product->height }}" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Weight</label><input type="number" name="weight" value="{{ $product->weight }}" class="form-control"></div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection
