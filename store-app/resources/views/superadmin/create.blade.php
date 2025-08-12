@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="h4 mb-0">Create New Tenant</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tenants.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="subdomain" class="form-label">Subdomain</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="subdomain" name="subdomain" required>
                        <span class="input-group-text">.example.test</span>
                    </div>
                </div>

                <div class="border-top pt-3 mb-3">
                    <h5 class="text-muted">Admin User Details</h5>
                </div>

                <div class="mb-3">
                    <label for="admin_email" class="form-label">Admin Email</label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                </div>

                <div class="mb-4">
                    <label for="admin_password" class="form-label">Admin Password</label>
                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Tenant & Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection