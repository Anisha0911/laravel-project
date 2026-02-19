@extends('layouts.auth')

@section('title', 'Login')

@php
$icon = 'bi-box-arrow-in-right';
$heading = 'Login';
$subheading = 'Sign in to your account';
@endphp

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input">
        <label class="form-check-label">Remember Me</label>
    </div>

    <div class="d-grid mb-3">
        <button class="btn btn-auth text-white">Login</button>
    </div>

    <div class="text-center">
        <a href="{{ route('password.request') }}">Forgot Password?</a>
    </div>
</form>
@endsection
