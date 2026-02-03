@extends('layouts.admin')
@section('content')
<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Edit Task</h2>
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>
    
    <!-- Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            

            <!-- Form -->
            <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
                @csrf
                @method('PUT')<!-- Important for update -->

                <div class="row g-3">
                    <!-- Task Title -->
                    <div class="col-12">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    </div>

                    <!-- Task Description -->
                    <div class="col-12">
                        <label for="name" class="form-label">Task Description</label>
                     <textarea name="description" rows="4" name="description" id="description" class="form-control" placeholder="Task description" required>{{ old('description', $task->description) }}</textarea>
                    </div>

                    <!-- Task - Project List  -->
                    <div class="col-12 col-md-4">
                        <label for="project_id" class="form-label">Project Name</label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ $project->id == $task->project_id ? 'selected' : '' }}>
                            {{ $project->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Task - Assign User -->
                    <div class="col-12 col-md-4">
                        <label for="user_id" class="form-label">Assign User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == $task->user_id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Task Satus -->
                    <div class="col-12 col-md-4">
                        <label for="role" class="form-label">Task Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Task Due Dates -->
                    <div class="col-12 col-md-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ $task->due_date->format('Y-m-d') }}" class="form-control">
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
