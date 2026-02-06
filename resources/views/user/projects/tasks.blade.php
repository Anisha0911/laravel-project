@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Tasks for Project: {{ $project->name }}</h3>

    @forelse($tasks as $task)
        <div class="card mb-2 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $task->title }}</strong>
                    <p>{{ $task->description ?? '' }}</p>
                </div>
                <div>
                    <a href="{{ route('user.tasks.show', $task->id) }}" class="btn btn-sm btn-primary">
                        View Task
                    </a>
                </div>
            </div>
        </div>
    @empty
        <p>No tasks assigned to you in this project.</p>
    @endforelse
</div>
@endsection
