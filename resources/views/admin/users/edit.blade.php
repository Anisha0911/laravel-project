@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Edit User</h2>
            <p class="text-muted small">Update the user details and role</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            <!-- Form -->
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                
                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-12">
                        <label for="name" class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-control shadow-sm"
                               placeholder="Enter full name" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-control shadow-sm"
                               placeholder="Enter email address" required>
                    </div>

                    <!-- Role -->
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fw-semibold">Role</label>
                        <select name="role" id="role" class="form-control shadow-sm" required>
                            <option value="user" @selected($user->role == 'user')>User</option>
                            <option value="admin" @selected($user->role == 'admin')>Admin</option>
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
                        <i class="bi bi-check-circle me-1"></i> Update User
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
