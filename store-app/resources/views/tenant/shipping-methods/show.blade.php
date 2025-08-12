<div class="card-header bg-dark text-white">
    Rate Configuration - {{ $method->rate ? 'Configured' : 'Not Configured' }}
</div>

<div class="card-body">
    @if($method->rate)
        <div class="row">
            <div class="col-md-6">
                <h5>Rate Details</h5>
                <p><strong>Name:</strong> {{ $method->rate->name }}</p>
                <p><strong>Price:</strong> ${{ number_format($method->rate->price, 2) }}</p>
                <p><strong>Condition:</strong> {{ $method->rate->condition_description }}</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ tenant_route('tenant.shipping-rates.edit', [
                    'subdomain' => tenant('subdomain'),
                    'shipping_method' => $method->id,
                    'rate' => $method->rate->id
                ]) }}" class="btn btn-sm btn-primary me-2">
                    Edit Rate
                </a>
                <form action="{{ tenant_route('tenant.shipping-rates.destroy', [
                    'subdomain' => tenant('subdomain'),
                    'shipping_method' => $method->id,
                    'rate' => $method->rate->id
                ]) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" 
                            onclick="return confirm('Delete this rate?')">
                        Delete Rate
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <p>No rate configured for this method.</p>
            <a href="{{ tenant_route('tenant.shipping-rates.create', [
                'subdomain' => tenant('subdomain'),
                'shipping_method' => $method->id
            ]) }}" 
               class="btn btn-sm btn-success">
                Add Rate
            </a>
        </div>
    @endif
</div>
