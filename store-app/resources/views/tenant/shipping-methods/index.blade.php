@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Shipping Methods</h1>
        <a href="{{ tenant_route('tenant.shipping-methods.create', ['subdomain' => tenant('subdomain')]) }}" 
           class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Create New Method
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Method Name</th>
                            <th>Transport Mode</th>
                            <th>Has Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($methods as $method)
                        <tr>
                            <td>{{ $method->id }}</td>
                            <td>{{ $method->name }}</td>
                            <td>
                                <span class="badge bg-{{ match($method->mode) {
                                    'air' => 'info',
                                    'sea' => 'primary',
                                    'land' => 'success',
                                    default => 'secondary'
                                } }}">
                                    {{ ucfirst($method->mode) }}
                                </span>
                            </td>
                            <td>
                                @if($method->rate)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ tenant_route('tenant.shipping-methods.show', [
                                        'subdomain' => tenant('subdomain'),
                                        'id' => $method->id
                                    ]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ tenant_route('tenant.shipping-methods.edit', [
                                        'subdomain' => tenant('subdomain'),
                                        'id' => $method->id
                                    ]) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ tenant_route('tenant.shipping-methods.destroy', [
                                        'subdomain' => tenant('subdomain'),
                                        'id' => $method->id
                                    ]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Delete this shipping method?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No shipping methods found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $methods->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
