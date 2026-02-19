@extends('layouts.admin')

@section('content')

<div class="container-fluid px-2 px-md-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <h2 class="fw-bold mb-0">User Dashboard</h2>
    </div>

    <!-- ===================== Stats Cards ===================== -->
    <div class="row g-4 mb-4">

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-modern bg-primary">
                <div>
                    <p class="mb-1 small text-light opacity-75">Total Projects</p>
                    <h3 class="fw-bold text-white mb-0">{{ $projects }}</h3>
                </div>
                <i class="bi bi-kanban"></i>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-modern bg-success">
                <div>
                    <p class="mb-1 small text-light opacity-75">Total Tasks</p>
                    <h3 class="fw-bold text-white mb-0">{{ $tasks }}</h3>
                </div>
                <i class="bi bi-list-task"></i>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card-modern bg-warning">
                <div>
                    <p class="mb-1 small text-light opacity-75">Completed Tasks</p>
                    <h3 class="fw-bold text-white mb-0">{{ $completedTasks }}</h3>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

    </div>


    <!-- ===================== My Tasks ===================== -->
    <div class="row g-4">

        <div class="col-12 col-lg-6">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 fw-bold">
                   <i class="bi bi-pencil-square"></i> My Tasks
                </div>

                <div class="table-responsive px-3 pb-3">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr class="small text-muted">
                            <th>Task</th>
                            <th>Project</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($tasks_status as $task_s)
                        <tr>
                            <td class="fw-semibold">{{ $task_s->title }}</td>
                            <td>{{ $task_s->project->name }}</td>
                            <td>
                                <form method="POST"
                                    action="{{ route('user.tasks.updateStatus', $task_s->id) }}">
                                    @csrf
                                    <select name="status"
                                            class="form-select form-select-sm rounded-pill"
                                            onchange="this.form.submit()">
                                        @foreach(['pending','in_progress','review','hold','completed'] as $s)
                                            <option value="{{ $s }}"
                                                {{ $task_s->status === $s ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_',' ', $s)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>


        <!-- ===================== My Projects ===================== -->
        <div class="col-12 col-lg-6">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 fw-bold">
                    <i class="bi bi-folder2-open"></i> My Projects
                </div>

                <div class="table-responsive px-3 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="small text-muted">
                            <tr>
                                <th>Project Name</th>
                                <th>Timeline</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                    <tbody>
                        @foreach($myProjects as $project)
                        <tr>
                            <td class="fw-semibold">{{ $project->name }}</td>
                            <td>
@php
$start = $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : 'N/A';
$end = $project->end_date ? \Carbon\Carbon::parse($project->end_date) : null;
$today = \Carbon\Carbon::today();

$tasks = $project->tasks()->where('user_id', auth()->id())->get();
$totalTasks = $tasks->count();
$completedTasks = $tasks->where('status', 'completed')->count();

$timelineText = 'N/A';
$timelineClass = 'bg-light text-dark border';

if ($end) {
    if ($totalTasks > 0 && $completedTasks == $totalTasks) {
        // Project completed
        $completedDate = $tasks->where('status', 'completed')->max('updated_at');
        $completedDate = \Carbon\Carbon::parse($completedDate);

        $diffDays = $end->diffInDays($completedDate, false);

        if ($diffDays > 0) {
            $timelineText = 'Completed ' . $diffDays . ' days late';
            $timelineClass = 'bg-danger text-white';
        } elseif ($diffDays < 0) {
            $timelineText = 'Completed ' . abs($diffDays) . ' days early';
            $timelineClass = 'bg-success text-white';
        } else {
            $timelineText = 'Completed on due date';
            $timelineClass = 'bg-secondary text-white';
        }
    } else {
        // Project not completed
        $diffDays = $today->diffInDays($end, false);

        if ($diffDays < 0) {
            $timelineText = 'Overdue by ' . abs($diffDays) . ' days';
            $timelineClass = 'bg-danger text-white';
        } elseif ($diffDays === 0) {
            $timelineText = 'Due Today';
            $timelineClass = 'bg-warning text-dark';
        } elseif ($diffDays === 1) {
            $timelineText = '1 day left';
            $timelineClass = 'bg-info text-dark';
        } else {
            $timelineText = $diffDays . ' days left';
            $timelineClass = 'bg-light text-dark border';
        }
    }
}
@endphp

<span class="badge bg-light text-dark border">
    {{ $start }} → {{ $end ? $end->format('d M Y') : 'N/A' }}
</span>
<span class="badge {{ $timelineClass }}">
    {{ $timelineText }}
</span>
                            </td>
                            <td>
                                @php
                                    $statusText = 'Active';
                                    $statusClass = 'bg-success text-white';

                                    if ($totalTasks > 0 && $completedTasks == $totalTasks) {
                                        $completedDate = $tasks->where('status', 'completed')->max('updated_at');
                                        $statusText = 'Completed on ' . \Carbon\Carbon::parse($completedDate)->format('d M Y');
                                        $statusClass = 'bg-secondary text-white';
                                    }
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
