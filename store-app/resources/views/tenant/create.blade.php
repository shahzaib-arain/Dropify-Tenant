@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h2 class="h4 mb-0">Create Tenant + Admin</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tenants.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subdomain</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="subdomain" required>
                        <span class="input-group-text">.example.test</span>
                    </div>
                </div>

                <div class="border-top pt-3 mb-3">
                    <h5 class="text-muted">Admin User Info</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Name</label>
                    <input type="text" class="form-control" name="admin_name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="admin_email" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="admin_password" required>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-circle me-1"></i> Create Tenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection