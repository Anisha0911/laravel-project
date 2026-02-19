@extends('layouts.admin')

@section('content')

<div class="container-fluid px-2 px-md-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold mb-0">Admin Dashboard</h2>

        <button id="darkModeToggle" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            🌙 Toggle Theme
        </button>
    </div>


    <!-- ===================== Stats Cards ===================== -->
    <div class="row g-4 mb-4">

        @php
        $cards = [
            ['title'=>'Total Users','count'=>$users->count(),'icon'=>'bi-people','color'=>'primary'],
            ['title'=>'Total Projects','count'=>$projects->count(),'icon'=>'bi-kanban','color'=>'success'],
            ['title'=>'Total Tasks','count'=>$tasks->count(),'icon'=>'bi-list-task','color'=>'warning'],
            ['title'=>'Admins','count'=>$users->where('role','admin')->count(),'icon'=>'bi-shield-lock','color'=>'danger'],
        ];
        @endphp

        @foreach($cards as $card)
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card-modern bg-{{ $card['color'] }}">
                <div>
                    <p class="mb-1 small text-light opacity-75">{{ $card['title'] }}</p>
                    <h3 class="fw-bold text-white">{{ $card['count'] }}</h3>
                </div>
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
        </div>
        @endforeach

    </div>


    <!-- ===================== Charts ===================== -->
    <div class="row g-4 mb-4">

        <div class="col-12 col-lg-6">
            <div class="card modern-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Task Status</h6>
                    <canvas id="taskChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card modern-card">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">User Roles</h6>
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>

    </div>


    <!-- ===================== Timeline + Tasks ===================== -->
    <div class="row g-4">

        <!-- Timeline -->
        <div class="col-12 col-xl-7">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 fw-bold">
                  <i class="bi bi-calendar-week"></i>  Project Timeline
                </div>

                <div class="card-body timeline-body">
                    @foreach($timeline as $item)
                    <div class="timeline-item">
                        <div>
                            <strong>{{ $item->user->name }}</strong>
                            <span class="text-muted small">updated task</span>
                            <div class="fw-semibold">{{ $item->title }}</div>
                            <small class="text-muted">
                                {{ $item->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- My Tasks -->
        <div class="col-12 col-xl-5">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 fw-bold">
                  <i class="bi bi-pencil-square"></i>  My Tasks
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
                        @foreach($tasks as $task)
                        <tr>
                            <td class="fw-semibold">{{ $task->title }}</td>
                            <td>{{ $task->project->name }}</td>
                            <td>
                                <form method="POST"
                                      action="{{ route('admin.tasks.updateStatus', $task->id) }}">
                                    @csrf
                                    <select name="status"
                                            class="form-select form-select-sm rounded-pill"
                                            onchange="this.form.submit()">
                                        @foreach(['pending','in_progress','review','hold','completed'] as $s)
                                        <option value="{{ $s }}"
                                            {{ $task->status === $s ? 'selected' : '' }}>
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

    </div>


    <!-- ===================== Project Overview + Users ===================== -->
    <div class="row g-4 mt-1">

        <div class="col-12 col-lg-3">
            <div class="card modern-card p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-kanban"></i> Project Overview</h6>

                <div class="d-flex justify-content-between mb-2">
                    <span>Open</span>
                    <span class="fw-bold">{{ $openProjects }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Completed</span>
                    <span class="fw-bold">{{ $completedProjects }}</span>
                </div>

                @php
                $total = $openProjects + $completedProjects;
                $progress = $total ? ($completedProjects/$total)*100 : 0;
                @endphp

                <div class="progress rounded-pill" style="height:10px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ $progress }}%">
                    </div>
                </div>

                <div class="small text-end mt-2 fw-semibold">
                    {{ round($progress) }}% Completed
                </div>
            </div>
        </div>

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
                                <th>Assigned Users</th>
                            </tr>
                        </thead>

                    <tbody>
                        @foreach($myProjects as $project)
                        <tr>
                            <td class="fw-semibold">{{ $project->name }}</td>
                          <td>
@php
    $start = $project->start_date 
        ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') 
        : 'N/A';

    $end = $project->end_date 
        ? \Carbon\Carbon::parse($project->end_date) 
        : null;

    $today = \Carbon\Carbon::today();

    $tasks = $project->tasks;
    $totalTasks = $tasks->count();
    $completedTasks = $tasks->where('status', 'completed')->count();

    $timelineText = 'N/A';
    $timelineClass = 'bg-light text-dark border';

    if ($end) {
        if ($totalTasks > 0 && $completedTasks == $totalTasks) {

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
<td>
    <div class="mb-1">
        @foreach($project->tasks as $task)
            @php
                $badgeClass = match($task->status) {
                    'completed' => 'bg-success text-white',
                    'in_progress' => 'bg-warning text-dark',
                    'pending' => 'bg-secondary text-white',
                    default => 'bg-light text-dark border'
                };
            @endphp
            <span class="badge {{ $badgeClass }}">
                {{ $task->user->name }}
            </span>
        @endforeach
    </div>

    @php
        $total = $project->tasks->count();
        $completed = $project->tasks->where('status','completed')->count();
        $percent = $total > 0 ? round(($completed / $total) * 100) : 0;
    @endphp

    <div class="progress" style="height:8px;">
        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
    </div>
</td>

                        </tr>
                        @endforeach
                    </tbody>

                    </table>
                </div>
            </div>
        </div>   

        <!-- Users -->
        <div class="col-12 col-lg-3">
            <div class="card modern-card h-100">
                <div class="card-header bg-transparent border-0 fw-bold">
                 <i class="bi bi-people"></i>   Users List
                </div>

                <div class="table-responsive px-3 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="small text-muted">
                        <tr>
                            <th>Name</th>                           
                            <th>Role</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>
                                <span class="badge rounded-pill
                                    {{ $user->role == 'admin'
                                    ? 'bg-primary'
                                    : 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
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
