@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Add Project</h2>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Project
        </a>
    </div>

        <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Form -->
             <form action="{{ route('admin.projects.store') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <!-- Project Name -->
                    <div class="col-12">
                        <label for="name" class="form-label">Project Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter project name" required>
                    </div>

                    <!-- Project Description -->
                    <div class="col-12">
                        <label for="name" class="form-label">Project Description</label>
                     <textarea name="description" rows="4" name="description" id="description" class="form-control" placeholder="Project description" required></textarea>
                    </div>

                    <!-- Project Assign -->
                    <div class="col-12 col-md-4">
                        <label for="role" class="form-label">Assign User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                        <option value="">Select User</option>
                             @foreach($users as $user)
                              @if($user->role === 'user')
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                              @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Project Dates -->
                    <div class="col-12 col-md-4">
                        <label for="name" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <label for="name" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
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
            </form>

         </div>
    </div>
</div>



<!-- Bootstrap Icons (needed for arrow/check icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
