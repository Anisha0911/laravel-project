@extends('layouts.auth')

@php
$icon = 'bi-key';
$heading = 'Forgot Password';
$subheading = 'We will send you reset link';
@endphp

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="d-grid">
        <button class="btn btn-auth text-white">Send Reset Link</button>
    </div>
</form>
@endsection
