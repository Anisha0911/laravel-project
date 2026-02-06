@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Add User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Form -->
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
                    </div>

                    <!-- Password -->
                    <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 6 characters" required>
                    </div>

                    <!-- Role -->
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                </div>

                <!-- Actions -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save User
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap Icons (needed for arrow/check icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
