@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Add Product</h2>
    <form method="POST" action="{{ route('tenant.products.store', ['subdomain' => tenant()->subdomain]) }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3"><label>Price</label><input type="number" name="price" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label>Length</label><input type="number" name="length" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Width</label><input type="number" name="width" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Height</label><input type="number" name="height" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Weight</label><input type="number" name="weight" class="form-control"></div>
            <div class="col-md-3 mb-3"><label>Initial Quantity</label><input type="number" name="quantity" class="form-control" required></div>
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
    </form>
</div>
@endsection
