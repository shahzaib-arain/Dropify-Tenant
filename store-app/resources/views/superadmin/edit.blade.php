@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Edit Tenant</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tenants.update', $tenant) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Tenant Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $tenant->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="subdomain" class="form-label">Subdomain</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="subdomain" name="subdomain" value="{{ $tenant->subdomain }}" required>
                        <span class="input-group-text">.example.test</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $tenant->email }}">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $tenant->phone }}">
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ $tenant->address }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection