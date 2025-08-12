@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Shipping Rate for: {{ $method->name }}</h2>

    <form method="POST" action="{{ tenant_route('tenant.shipping-rates.update', ['shipping_method' => $method->id, 'rate' => $rate->id, 'subdomain' => tenant()->subdomain]) }}">
        @csrf
        @method('PUT')

        @include('tenant.shipping-rates._form')

        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
