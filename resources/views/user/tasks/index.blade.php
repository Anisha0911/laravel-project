@extends('layouts.admin')

<style>
/* Filter bar */
.filter-wrapper {
    overflow: hidden;
}
.filter-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 5px;
}
.filter-scroll::-webkit-scrollbar {
    height: 4px;
}
.filter-scroll::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

/* Filter box */
.filter-box {
    min-width: 120px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    cursor: pointer;
    transition: all 0.2s ease;
}
.filter-box strong {
    font-size: 16px;
}
.filter-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}

/* Date filter */
.date-filter {
    min-width: 170px;
    gap: 6px;
}
.date-filter input {
    border: none;
    padding: 0;
    font-size: 13px;
}

.filter-box.active {
    background: #0d6efd;
    color: #fff !important;
    border-color: #0d6efd;
}
.filter-box.active strong,
.filter-box.active span {
    color: #fff;
}
</style>

@section('content')
<div class="container mt-5">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="fw-bold mb-3 mb-md-0">All Tasks</h2>
    </div>

    <!-- Filters -->
    <div class="filter-wrapper mb-4">
        <div class="filter-scroll">
            
<div class="filter-box text-info" data-filter="all">
    <span>All</span>
    <strong>{{ $tasks->count() }}</strong>
</div>

            <div class="filter-box text-success" data-filter="completed">
                <span>Completed</span>
                <strong>{{ $completedCount }}</strong>
            </div>

            <div class="filter-box text-primary" data-filter="in_progress">
                <span>In Progress</span>
                <strong>{{ $inProgressCount }}</strong>
            </div>

            <div class="filter-box text-dark" data-filter="hold">
                <span>On Hold</span>
                <strong>{{ $holdCount }}</strong>
            </div>

            <div class="filter-box text-secondary" data-filter="pending">
                <span>Pending</span>
                <strong>{{ $pendingCount }}</strong>
            </div>

            <div class="filter-box text-success" data-filter="in_time">
                <span>In Time</span>
                <strong>{{ $inTimeCount }}</strong>
            </div>

            <div class="filter-box text-danger" data-filter="delayed">
                <span>Delayed</span>
                <strong>{{ $delayedCount }}</strong>
            </div>

            <!-- Date Filter -->
            <div class="filter-box date-filter">
                <i class="bi bi-calendar-event"></i>
                <input type="date" id="dateFilter" class="form-control form-control-sm">
            </div>

        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Task</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody id="taskTableBody">
                        @forelse($tasks as $task)
                            @php
                                $statusMap = [
                                    'pending' => ['secondary', 'bi-clock'],
                                    'in_progress' => ['primary', 'bi-arrow-repeat'],
                                    'review' => ['warning', 'bi-eye'],
                                    'hold' => ['dark', 'bi-pause-circle'],
                                    'completed' => ['success', 'bi-check-circle'],
                                ];

                                $priorityMap = [
                                    'low' => ['success', 'bi-arrow-down'],
                                    'medium' => ['warning', 'bi-dash-circle'],
                                    'high' => ['danger', 'bi-arrow-up'],
                                    'urgent' => ['dark', 'bi-exclamation-triangle'],
                                ];

                                $statusColor = $statusMap[$task->status][0] ?? 'secondary';
                                $statusIcon  = $statusMap[$task->status][1] ?? 'bi-question-circle';

                                $priorityColor = $priorityMap[$task->priority][0] ?? 'secondary';
                                $priorityIcon  = $priorityMap[$task->priority][1] ?? 'bi-dash';
                            @endphp

                            <tr>
                                <td class="fw-semibold">
                                    {{ $task->title }}
                                    <div class="small text-muted">{{ $task->project->name ?? '' }}</div>
                                </td>
                                <td class="text-muted">
                                    {{ $task->created_date ?? $task->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusColor }}">
                                        <i class="bi {{ $statusIcon }}"></i>
                                        {{ ucfirst(str_replace('_',' ',$task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $priorityColor }}">
                                        <i class="bi {{ $priorityIcon }}"></i>
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('user.tasks.show', $task) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No tasks assigned.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filters = document.querySelectorAll('.filter-box[data-filter]');
    const tableBody = document.getElementById('taskTableBody');
    const dateInput = document.getElementById('dateFilter');

    function fetchFilteredTasks(status = '', date = '') {
        const params = new URLSearchParams({status, date});
        fetch(`{{ route('user.tasks.ajaxFilter') }}?${params}`)
            .then(res => res.json())
            .then(data => {
                // Update table rows
                tableBody.innerHTML = data.rows;

                // Update counts
                Object.entries(data.counts).forEach(([key, val]) => {
                    const el = document.querySelector(`.filter-box[data-filter="${key}"] strong`);
                    if(el) el.textContent = val;
                });
            })
            .catch(err => console.error(err));
    }

    // Status filter click
filters.forEach(f => {
    f.addEventListener('click', function() {
        filters.forEach(box => box.classList.remove('active'));
        this.classList.add('active');

        const status = this.dataset.filter; // "all" or actual status
        const date = dateInput.value || '';

        // Treat "all" separately by sending 'all' to controller
        fetchFilteredTasks(status, date);
    });
});

    // Date filter change
    dateInput.addEventListener('change', function() {
        const activeFilter = document.querySelector('.filter-box.active');
        const status = activeFilter ? activeFilter.dataset.filter : '';
        fetchFilteredTasks(status, this.value);
    });
});
</script>

@endsection
