<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                @auth
                    @if(auth()->user()->role->name === 'superadmin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('superadmin.dashboard') }}">Admin Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            @php
                                $subdomain = tenant('subdomain') ?? (app('currentTenant')->subdomain ?? null);
                            @endphp
                            @if($subdomain)
                                <a class="nav-link" href="{{ route('tenant.dashboard', ['subdomain' => $subdomain]) }}">My Dashboard</a>
                            @else
                                <span class="nav-link text-warning">No tenant loaded</span>
                            @endif
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    @php
                        $customerId = session('active_customer_id');
                        $customer = $customerId ? \App\Models\Customer::find($customerId) : null;

                        // Prevent error if carts() relation doesn't exist
                        $cartCount = ($customer && method_exists($customer, 'carts'))
                            ? optional($customer->carts()->open()->withCount('items')->first())->items_count
                            : 0;

                        // Get all customers by tenant only (Option 2)
                        $allCustomers = \App\Models\Customer::where('tenant_id', tenant('id'))->get();
                    @endphp

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tenant.cart.index', ['subdomain' => tenant('subdomain')]) }}">
                            <i class="fas fa-shopping-cart"></i> Cart
                            @if($cartCount)
                                <span class="badge bg-danger">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Customer Switch Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="customerDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $customer ? $customer->name : 'Select Customer' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customerDropdown">
                            @forelse($allCustomers as $cust)
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.switch', ['id' => $cust->id]) }}">
                                        {{ $cust->name }}
                                        @if($customer && $customer->id === $cust->id)
                                            <span class="text-success">(Active)</span>
                                        @endif
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item text-muted">No customers</span></li>
                            @endforelse
                        </ul>
                    </li>
                @endauth

                {{-- Guest / User Dropdown --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
