@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Customers</h1>
<<<<<<< HEAD
        <a href="{{ tenant_route('tenant.customers.create', ['subdomain' => tenant()->subdomain]) }}" class="btn btn-success">
            Add Customer
        </a>
=======
      <a href="{{ route('tenant.customers.create', ['subdomain' => request()->route('subdomain') ?? tenant()->subdomain]) }}"
   class="btn btn-success">
   Add Customer
</a>

>>>>>>> a9176ecda47fd623069b9d3516d19a4bcb2e89e4
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
                                            <!-- View Button -->
                                            <a href="{{ tenant_route('tenant.customers.show', [
                                                'subdomain' => tenant()->subdomain,
                                                'id' => $customer->id
                                            ]) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ tenant_route('tenant.customers.edit', [
                                                'subdomain' => tenant()->subdomain,
                                                'id' => $customer->id
                                            ]) }}" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ tenant_route('tenant.customers.destroy', [
                                                'subdomain' => tenant()->subdomain,
                                                'id' => $customer->id
                                            ]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
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
<<<<<<< HEAD

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
=======
                {{ $customers->links() }}
>>>>>>> a9176ecda47fd623069b9d3516d19a4bcb2e89e4
            @endif
        </div>
    </div>
</div>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> a9176ecda47fd623069b9d3516d19a4bcb2e89e4
