@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Customers</h1>
      <a href="{{ route('tenant.customers.create', ['subdomain' => request()->route('subdomain') ?? tenant()->subdomain]) }}"
   class="btn btn-success">
   Add Customer
</a>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($customers->isEmpty())
                <div class="alert alert-info">No customers found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Addresses</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->addresses->count() }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('tenant.customers.show', ['subdomain' => tenant()->subdomain, 'customer' => $customer->id]) }}" 

                                           class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tenant.customers.edit', ['subdomain' => tenant()->subdomain, 'customer' => $customer->id]) }}" 
 
                                           class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('tenant.customers.destroy', ['subdomain' => tenant()->subdomain, 'customer' => $customer->id]) }}" 
                                              method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $customers->links() }}
            @endif
        </div>
    </div>
</div>
@endsection