@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Add User</h2>
            <p class="text-muted small">Create a new user for your system</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <!-- Form -->
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="name"
                               class="form-control shadow-sm"
                               placeholder="Enter full name" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control shadow-sm"
                               placeholder="Enter email address" required>
                    </div>

                    <!-- Password -->
                    <div class="col-12">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="password"
                               class="form-control shadow-sm"
                               placeholder="Minimum 6 characters" required>
                    </div>

                    <!-- Role -->
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select name="role" id="role" class="form-control shadow-sm" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                </div>

                <!-- Actions -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-secondary rounded-pill px-4">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Save User
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
