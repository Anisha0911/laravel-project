@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Add Project</h2>
            <p class="text-muted small">Create and assign a new project</p>
        </div>

        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Projects
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-folder-plus text-primary me-2"></i> Project Details
            </h5>
            <small class="text-muted">Fill project information and assign user</small>
        </div>

        <div class="card-body px-4 pb-4">

            <form action="{{ route('admin.projects.store') }}" method="POST">
                @csrf

                <div class="row g-4">

                    <!-- Project Name -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Name</label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg"
                               placeholder="Enter project name"
                               required>
                    </div>

                    <!-- Project Description -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Project Description</label>
                        <textarea name="description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Write project description..."
                                  required></textarea>
                    </div>

                    <!-- Assign User -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Assign User</label>
                        <select name="user_id" class="form-control form-control-lg" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                @if($user->role === 'user')
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-control form-control-lg">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" class="form-control form-control-lg">
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Save Project
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
