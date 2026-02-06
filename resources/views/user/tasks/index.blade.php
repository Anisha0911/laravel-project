@extends('layouts.admin')

@section('content')

<div class="container mt-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">Your Tasks</h2>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $task->title }}
                                </td>

                                <td class="text-muted">
                                    {{ $task->project->name ?? '—' }}
                                </td>

                                <td>
                                    @php
                                        $statusClass = match($task->status) {
                                            'completed' => 'success',
                                            'in_progress' => 'warning',
                                            'pending' => 'secondary',
                                            default => 'dark'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>

                                <td class="text-muted">
                                    {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('user.tasks.show', $task) }}"
                                       class="btn btn-sm btn-primary">
                                        View Task
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No tasks assigned to you.
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
