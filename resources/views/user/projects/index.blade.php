@extends('layouts.admin')
@section('content')

<div class="container mt-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Projects</h2>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Project Name</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $project->name }}
                                </td>

                                <td class="text-muted">
                                    {{ $project->description ?? '—' }}
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A' }}
                                        —
                                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : 'N/A' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @php
                                        $task = $project->tasks()
                                            ->where('user_id', auth()->id())
                                            ->first();
                                    @endphp

                                    @if($task)
                                        <a href="{{ route('user.projects.tasks', $project->id) }}"
                                           class="btn btn-sm btn-primary">
                                            View My Tasks
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">No tasks</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No projects assigned to you.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
