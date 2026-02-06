@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Header: Title + Add User button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Users</h2>
        <a href="/admin/users/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add User
        </a>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)

 @if($user->role !== 'admin')  <!-- Hide Admin ( Remove This line to Show Admin ) -->
                         <tr>
                            <td class="fw-medium">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
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
                                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form method="POST" action="/admin/users/{{ $user->id }}" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

    @endif <!-- Hide Admin ( Remove This line to Show Admin ) -->
                            @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (required for pencil/trash/add icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection





