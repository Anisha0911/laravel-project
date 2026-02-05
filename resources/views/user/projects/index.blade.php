@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <h3>My Projects</h3>

    @forelse($projects as $project)
        <div class="card mb-2 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $project->name }}</strong>
                    <p class="mb-0">{{ $project->description ?? '' }}</p>
                </div>
                <div>
                    @php
                        // Get the first task assigned to this user for this project
                        $task = $project->tasks()->where('user_id', auth()->id())->first();
                    @endphp

                    @if($task)
<a href="{{ route('user.projects.tasks', $project->id) }}" class="btn btn-sm btn-primary">
    View My Tasks
</a>
                    @else
                        <span class="text-muted">No tasks assigned</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p>No projects assigned to you.</p>
    @endforelse
</div>
@endsection
