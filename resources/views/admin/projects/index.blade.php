@extends('layouts.admin')
@section('content')

<div class="container mt-5">
    <!-- Header: Title + Add User button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Projects</h2>
        <a href="/admin/projects/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Project
        </a>
    </div>

<!-- Project Table Card -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Duration</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td class="fw-medium">{{ $project->name }}</td>
                        <td class="fw-medium">{{ $project->description }}</td>
                        <!-- Assigned User -->
                        <td>
                            {{ $project->user->name ?? '—' }}
                        </td>

                        <!-- Duration -->
                        <td>
                            <span class="text-muted">
                                {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}
                                —
                                {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <a href="{{ route('admin.projects.edit', $project->id) }}"
                               class="btn btn-sm btn-outline-warning my-1">
                                <i class="bi bi-pencil-fill"></i>
                            </a>

                        <!-- Delete Modal Reuseable Component -->
                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#globalDeleteModal"
                                data-action="{{ route('admin.projects.destroy', $project->id) }}">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">No projects found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

@endsection
