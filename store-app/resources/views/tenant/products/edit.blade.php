@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Product</h2>

    <form method="POST" action="{{ tenant_route('tenant.products.update', $product->id) }}">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" 
                       value="{{ old('name', $product->name) }}" 
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="sku">SKU <span class="text-danger">*</span></label>
                <input id="sku" type="text" name="sku" 
                       value="{{ old('sku', $product->sku) }}" 
                       class="form-control @error('sku') is-invalid @enderror" required>
                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="description">Description</label>
            <textarea id="description" name="description" 
                      class="form-control @error('description') is-invalid @enderror"
                      rows="3">{{ old('description', $product->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="price">Price <span class="text-danger">*</span></label>
                <input id="price" type="number" step="0.01" name="price" 
                       value="{{ old('price', $product->price) }}" 
                       class="form-control @error('price') is-invalid @enderror" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="length">Length</label>
                <input id="length" type="number" step="0.01" name="length" 
                       value="{{ old('length', $product->length) }}" 
                       class="form-control @error('length') is-invalid @enderror">
                @error('length') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="width">Width</label>
                <input id="width" type="number" step="0.01" name="width" 
                       value="{{ old('width', $product->width) }}" 
                       class="form-control @error('width') is-invalid @enderror">
                @error('width') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="height">Height</label>
                <input id="height" type="number" step="0.01" name="height" 
                       value="{{ old('height', $product->height) }}" 
                       class="form-control @error('height') is-invalid @enderror">
                @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ tenant_route('tenant.products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
