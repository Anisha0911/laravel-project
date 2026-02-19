@extends('layouts.auth')

@php
$icon = 'bi-person-plus';
$heading = 'Register';
$subheading = 'Create a new account';
@endphp

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="d-grid mb-3">
        <button class="btn btn-auth text-white">Register</button>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}">Already registered?</a>
    </div>
</form>
@endsection
