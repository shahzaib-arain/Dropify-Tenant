@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h2 class="h5 mb-0">Profile Information</h2>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h2 class="h5 mb-0">Update Password</h2>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">
            <h2 class="h5 mb-0">Delete Account</h2>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
