@extends('layouts.auth')

@php
$icon = 'bi-shield-lock';
$heading = 'Reset Password';
$subheading = 'Create a new password';
@endphp

@section('content')
<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required>
    </div>

    <div class="mb-3">
        <label>New Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="d-grid">
        <button class="btn btn-auth text-white">Reset Password</button>
    </div>
</form>
@endsection
