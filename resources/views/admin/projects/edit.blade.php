@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-pencil-square text-primary me-2"></i> Edit Project
        </h3>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
    </div>

    <!-- Card -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">

            <!-- Project Edit Form -->
            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- Project Name -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Name</label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg" 
                               value="{{ old('name', $project->name) }}" 
                               placeholder="Enter project name"
                               required>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Description</label>
                        <textarea name="description" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="Write project description..."
                                  required>{{ old('description', $project->description) }}</textarea>
                    </div>

                    <!-- Assign User -->
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">Assign User</label>
                        <select name="user_id" class="form-select">
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

                    <!-- Start Date -->
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" 
                               class="form-control" 
                               value="{{ old('start_date', $project->start_date) }}">
                    </div>

                    <!-- End Date -->
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" 
                               class="form-control" 
                               value="{{ old('end_date', $project->end_date) }}">
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-light border">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Update Project
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
