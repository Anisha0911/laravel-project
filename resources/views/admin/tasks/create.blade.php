@extends('layouts.admin')
@section('content')

<div class="container-fluid mt-4">

    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Add Task</h2>
            <p class="text-muted small">Create and assign a new task</p>
        </div>

        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Tasks
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-1">
                <i class="bi bi-plus-square text-primary me-2"></i> Task Details
            </h5>
            <small class="text-muted">Fill task information and assign to a user</small>
        </div>

        <div class="card-body px-4 pb-4">

            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf
                <div class="row g-4">

                    <!-- Task Title -->
                    <div class="col-12">
                        <label for="title" class="form-label fw-semibold">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg" placeholder="Enter Task Title" required>
                    </div>

                    <!-- Task Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Task Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Write task description..." required></textarea>
                    </div>

                    <!-- Project -->
                    <div class="col-12 col-md-4">
                        <label for="project_id" class="form-label fw-semibold">Project</label>
                        <select name="project_id" id="project_id" class="form-control form-control-lg" required>
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Assigned User -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label fw-semibold">Assign User</label>
                        <select name="user_id" id="user_id" class="form-control form-control-lg" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                @if($user->role === 'user')
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label fw-semibold">Task Status</label>
                        <select name="status" id="status" class="form-control form-control-lg" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="hold">Hold</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div class="col-12 col-md-4">
                        <label for="priority" class="form-label fw-semibold">Task Priority</label>
                        <select name="priority" id="priority" class="form-control form-control-lg" required>
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <!-- Creation Date -->
                    <div class="col-12 col-md-4">
                        <label for="created_date" class="form-label fw-semibold">Creation Date</label>
                        <input type="date" name="created_date" id="created_date" class="form-control form-control-lgform-control form-control-lg" value="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Due Date -->
                    <div class="col-12 col-md-4">
                        <label for="due_date" class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control form-control-lg">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 mt-4 d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-1"></i> Save Task
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

</div>

@endsection
