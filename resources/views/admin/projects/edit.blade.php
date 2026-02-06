@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Edit Project</h2>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Project
        </a>
    </div>
    
    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Form -->
            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Project Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $project->name) }}" required>
                    </div>

                    <!-- Project Description -->
                    <div class="col-12">
                        <label for="description" class="form-label">Project Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description', $project->description) }}</textarea>
                    </div>

                    <!-- Project Assign -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label">Assign User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            @if($user->role === 'user')
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $project->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Project Dates -->
                    <div class="col-12 col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $project->start_date) }}">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $project->end_date) }}">
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Project
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
@endsection
