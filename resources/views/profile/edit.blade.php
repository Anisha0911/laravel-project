@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">My Profile</h3>
    </div>

    <div class="row g-4">

        <!-- Profile Info Card -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-circle me-2"></i> Profile Information
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Password Update Card -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-lock me-2"></i> Change Password
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 border-danger">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-trash me-2"></i> Delete Account
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
