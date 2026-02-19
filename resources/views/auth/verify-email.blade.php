@extends('layouts.auth')

@php
$icon = 'bi-envelope-check';
$heading = 'Verify Email';
$subheading = 'Check your inbox to verify account';
@endphp

@section('content')

@if (session('status') == 'verification-link-sent')
<div class="alert alert-success">Verification link sent again.</div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <div class="d-grid mb-3">
        <button class="btn btn-auth text-white">Resend Verification Email</button>
    </div>
</form>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <div class="text-center">
        <button class="btn btn-link">Logout</button>
    </div>
</form>

@endsection
