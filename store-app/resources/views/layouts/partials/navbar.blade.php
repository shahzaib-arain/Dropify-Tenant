<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <!-- Brand/logo link -->
        <a class="navbar-brand" 
           href="{{ isSuperAdmin() 
               ? route('superadmin.dashboard') 
               : tenant_route('tenant.dashboard', ['subdomain' => tenant()->subdomain]) }}">
            {{ config('app.name', 'Laravel') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Left Menu --}}
            <ul class="navbar-nav me-auto">
                @auth
                    {{-- Super Admin Links --}}
                    @if(isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                                <i class="fas fa-user-shield"></i> Admin Dashboard
                            </a>
                        </li>
                    @endif

                    {{-- Tenant Admin Links --}}
                    @if(isTenantAdmin())
                   <li class="nav-item">
    <a class="nav-link" href="{{ dashboard_url() }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ tenant_route('tenant.shipping-methods.index', ['subdomain' => tenant()->subdomain]) }}">
                                <i class="fas fa-truck"></i> Shipping
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ tenant_route('tenant.products.index', ['subdomain' => tenant()->subdomain]) }}">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            {{-- Right Menu --}}
            <ul class="navbar-nav ms-auto">
                @auth
                    @if(isTenantAdmin())
                        {{-- Cart --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ tenant_route('tenant.cart.index', ['subdomain' => tenant()->subdomain]) }}">
                                <i class="fas fa-shopping-cart"></i> Cart
                                @if($cartCount)
                                    <span class="badge bg-danger">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>

                        {{-- Orders --}}
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ tenant_route('tenant.orders.index', ['subdomain' => tenant()->subdomain]) }}">
                                <i class="fas fa-list"></i> Orders
                            </a>
                        </li>

                        {{-- Customer Switch Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="customerDropdown"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $customer ? $customer->name : 'Select Customer' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="customerDropdown">
                                @forelse($allCustomers as $cust)
                                    <li>
                                        <a class="dropdown-item" 
                                           href="{{ tenant_route('customer.switch', ['id' => $cust->id, 'subdomain' => tenant()->subdomain]) }}">
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
                    @endif
                @endauth

                {{-- Authentication Links --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ isSuperAdmin() 
                                    ? route('profile.edit') 
                                    : tenant_route('profile.edit', ['subdomain' => tenant()->subdomain]) }}">
                                    <i class="fas fa-user-circle me-1"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
