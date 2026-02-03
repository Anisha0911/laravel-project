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
            <form method="POST" action="{{ route('admin.projects.update', $project->id) }}">
                @csrf
                @method('PUT')

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

                    
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Project Name
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $project->name) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-200"
                           required>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Description
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-200">{{ old('description', $project->description) }}</textarea>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Status
                    </label>
                    <select name="status"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-indigo-200">
                        <option value="active" {{ $project->status == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>
                </div>

            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.projects.index') }}"
                   class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Update Project
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
