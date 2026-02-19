@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Users</h2>
            <p class="text-muted small">Manage all users in your system</p>
        </div>

        <a href="/admin/users/create" class="btn btn-primary rounded-pill px-3 btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add User
        </a>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

                <!-- Left: Title -->
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        User List
                    </h5>
                    <small class="text-muted">All registered users and their roles</small>
                </div>

                <!-- Right: Search -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="w-auto">
                    <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control border-0 shadow-none"
                            placeholder="Search tasks...">
                    </div>
                </form> 

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        @if($user->role !== 'admin') <!-- Hide Admin (remove this line to show admin) -->
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td class="text-muted small">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger">{{ ucfirst($user->role) }}</span>
                                @elseif($user->role === 'manager')
                                    <span class="badge bg-warning text-dark">{{ ucfirst($user->role) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/admin/users/{{ $user->id }}/edit"
                                   class="btn btn-sm btn-outline-primary rounded-circle me-1">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-circle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#globalDeleteModal"
                                        data-action="{{ route('admin.users.destroy', $user->id) }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                        @endif <!-- Hide Admin -->
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-person-fill-x fs-3"></i>
                                <p class="mb-0">No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Instant Change While Typing -->
<script>
document.querySelector('input[name="search"]').addEventListener('keyup', function() {
    this.form.submit();
});
</script>
@endsection
