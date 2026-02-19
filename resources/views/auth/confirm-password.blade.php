@extends('layouts.auth')

@php
$icon = 'bi-lock-fill';
$heading = 'Confirm Password';
$subheading = 'Secure area verification';
@endphp

@section('content')
<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="d-grid">
        <button class="btn btn-auth text-white">Confirm</button>
    </div>
</form>
@endsection
