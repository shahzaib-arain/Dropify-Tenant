@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Create Shipping Rate for: {{ $method->name }}</h2>

    <form method="POST" action="{{ tenant_route('tenant.shipping-rates.store', ['shipping_method' => $method->id, 'subdomain' => tenant()->subdomain]) }}">
        @csrf

        @include('tenant.shipping-rates._form')

        <button class="btn btn-success mt-3">Create</button>
    </form>
</div>
@endsection
