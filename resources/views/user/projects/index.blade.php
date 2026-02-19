@extends('layouts.admin')
@section('content')

<div class="container-fluid mt-4">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">My Projects</h2>
            <p class="text-muted small mb-0">Projects assigned to you with task tracking access.</p>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('user.projects.index') }}" class="w-auto">
            <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-0 shadow-none"
                       placeholder="Search projects...">
            </div>
        </form>
    </div>

    <!-- ================= FILTER TABS ================= -->
    <div class="d-flex flex-wrap gap-2 mb-3">

        <a href="{{ route('user.projects.index') }}" 
           class="btn btn-sm {{ request('status') == null ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3">
           All
        </a>

        <a href="{{ route('user.projects.index', ['status'=>'active']) }}" 
           class="btn btn-sm {{ request('status')=='active' ? 'btn-success' : 'btn-outline-success' }} rounded-pill px-3">
           Active
        </a>

        <a href="{{ route('user.projects.index', ['status'=>'completed']) }}" 
           class="btn btn-sm {{ request('status')=='completed' ? 'btn-secondary' : 'btn-outline-secondary' }} rounded-pill px-3">
           Completed
        </a>

    </div>

    <!-- ================= PROJECT TABLE ================= -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Project</th>
                            <th>Description</th>
                            <th>Timeline</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($projects as $project)
                        <tr>

                            <!-- Project Name -->
                            <td class="fw-semibold">
                                <i class="bi bi-folder-fill text-primary me-1"></i>
                                {{ $project->name }}
                            </td>

                            <!-- Description -->
                            <td class="text-muted small">
                                {{ \Illuminate\Support\Str::limit($project->description, 60) ?? '—' }}
                            </td>

                            <!-- TIMELINE -->
                            
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

                            <!-- STATUS -->
                            <td class="text-center">
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


                            <!-- Action -->
                            <td class="text-center">
                                @if($tasks->count() > 0)
                                    <a href="{{ route('user.projects.tasks', $project->id) }}" 
                                       class="btn btn-sm btn-primary rounded-pill px-3">
                                       <i class="bi bi-list-task me-1"></i> My Tasks
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border">No tasks</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-folder-x fs-4"></i><br>
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
<!-- Instant Change While Typing -->
<script>
document.querySelector('input[name="search"]').addEventListener('keyup', function() {
    this.form.submit();
});
</script>
@endsection
