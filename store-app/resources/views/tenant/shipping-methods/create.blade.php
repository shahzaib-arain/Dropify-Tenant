    @extends('layouts.app')

    @section('content')
    <div class="container py-4">
        <h2 class="mb-4">Create Shipping Method</h2>
        
        <div class="card">
            <div class="card-body">
                <form action="{{ tenant_route('tenant.shipping-methods.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Method Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="mode" class="form-label">Transport Mode</label>
                        <select class="form-select" id="mode" name="mode" required>
                            <option value="air">Air</option>
                            <option value="sea">Sea</option>
                            <option value="land">Land</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Method</button>
                    <a href="{{ route('tenant.shipping-methods.index', ['subdomain' => tenant('subdomain')]) }}" 
                    class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    @endsection