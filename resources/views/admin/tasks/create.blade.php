@extends('layouts.admin')
@section('content')

<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Add Task</h2>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Form -->
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <!-- Task Title -->
                    <div class="col-12">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Task Title" required>
                    </div>

                    <!-- Task Description -->
                    <div class="col-12">
                        <label for="name" class="form-label">Task Description</label>
                     <textarea name="description" rows="4" name="description" id="description" class="form-control" placeholder="Task description" required></textarea>
                    </div>

                    <!-- Task - Project List  -->
                    <div class="col-12 col-md-4">
                        <label for="project_id" class="form-label">Project Name</label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            <option value="">Select Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Task - Assign User -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label">Assign User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Select User</option>
                        @foreach($users as $user)
                         @if($user->role === 'user')
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                         @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Task Satus -->
                    <div class="col-12 col-md-4">
                        <label for="role" class="form-label">Task Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="">Select Task Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="hold">Hold</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Task Priority -->
                    <div class="col-12 col-md-4">
                        <label for="priority" class="form-label">Task Priority</label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <!-- Task Creation Date -->
                    <div class="col-12 col-md-4">
                        <label for="created_date" class="form-label">Task Creation Date</label>
                        <input type="date" name="created_date" id="created_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Task Due Dates -->
                    <div class="col-12 col-md-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
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
                
<!-- Bootstrap Icons (needed for arrow/check icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
