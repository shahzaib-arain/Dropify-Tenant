@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Edit Tenant</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tenants.update', $tenant->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $tenant->name }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Subdomain</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="subdomain" value="{{ $tenant->subdomain }}" required>
                        <span class="input-group-text">.example.test</span>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection