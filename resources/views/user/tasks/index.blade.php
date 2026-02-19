@extends('layouts.admin')

<style>
/* ================= FILTER BAR ================= */
.filter-wrapper { overflow: hidden; }
.filter-scroll { display: flex; gap: 14px; overflow-x: auto; padding-bottom: 8px; }
.filter-scroll::-webkit-scrollbar { height: 5px; }
.filter-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

/* Filter Cards */
.filter-box {
    min-width: 130px;
    background: #fff;
    border: 1px solid #f0f0f0;
    border-radius: 14px;
    padding: 2px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    cursor: pointer;
    transition: 0.25s ease;
}
.filter-box strong { font-size: 18px; font-weight: 700; }
.filter-box:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.filter-box.active {
    background: linear-gradient(135deg,#0d6efd,#6610f2);
    color: #fff !important;
    border-color: transparent;
}
.filter-box.active strong,
.filter-box.active span { color: #fff; }

/* Table UI */
.table-hover tbody tr:hover { background: #f9fbff; }
.sticky-header thead th {
    position: sticky;
    top: 0;
    background: #212529;
    z-index: 5;
    color: #fff;
}

/* Highlight pinned tasks */
.tr-pinned { border-left: 5px solid #ffc107 !important; background: #fffdf3; }

/* Cards Hover */
.card { border-radius: 16px; transition: .25s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,.07); }

/* Status badge bigger */
.badge { padding: 6px 12px; font-size: 12px; border-radius: 20px; }

/* Action Button */
.btn-view { border-radius: 20px; padding: 4px 14px; font-size: 13px; }
</style>

@section('content')
<div class="container-fluid mt-4">

    <!-- ================= HEADER ================= -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Tasks</h2>
            <p class="text-muted small mb-0">Manage all tasks assigned to you</p>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('user.tasks.index') }}" class="w-auto">
            <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 ps-3">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-0 shadow-none"
                       placeholder="Search tasks...">
            </div>
        </form>      
    </div>

    <!-- ================= FILTER CARDS ================= -->
    <div class="filter-wrapper mb-4">
        <div class="filter-scroll">
            <div class="filter-box active" data-filter="all"><span>All Tasks</span><strong>{{ $tasks->count() }}</strong></div>
            <div class="filter-box text-success" data-filter="completed"><span>Completed</span><strong>{{ $completedCount }}</strong></div>
            <div class="filter-box text-primary" data-filter="in_progress"><span>In Progress</span><strong>{{ $inProgressCount }}</strong></div>
            <div class="filter-box text-dark" data-filter="hold"><span>On Hold</span><strong>{{ $holdCount }}</strong></div>
            <div class="filter-box text-secondary" data-filter="pending"><span>Pending</span><strong>{{ $pendingCount }}</strong></div>
            <div class="filter-box text-warning" data-filter="review"><span>Review</span><strong>{{ $reviewCount ?? 0 }}</strong></div>
            <div class="filter-box text-danger" data-filter="overdue"><span>Overdue</span><strong>{{ $overdueCount ?? 0 }}</strong></div>
            <div class="filter-box text-danger" data-filter="high_priority"><span>High Priority</span><strong>{{ $highPriorityCount ?? 0 }}</strong></div>

            <!-- Date Picker -->
            <div class="filter-box date-filter">
                <i class="bi bi-calendar-event"></i>
                <input type="date" id="dateFilter" class="form-control form-control-sm border-0">
            </div>

            <!-- Reset Filters -->
            <div class="filter-box text-danger" id="clearFilters">Reset</div>
        </div>
    </div>

    <!-- ================= BULK ACTIONS ================= -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <input type="checkbox" id="selectAll" class="form-check-input me-2">
            <label for="selectAll" class="form-check-label fw-semibold">Select All</label>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-success rounded-pill px-3" id="bulkComplete">
                <i class="bi bi-check-circle"></i> Complete
            </button>
            <button class="btn btn-sm btn-danger rounded-pill px-3" id="bulkDelete">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>

    <!-- ================= TASK TABLE ================= -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 sticky-header">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="headerCheckbox"></th>
                            <th>Task</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="taskTableBody">
                        @include('user.tasks.partials.task_rows', ['tasks' => $tasks])
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
    const selectAll = document.getElementById('selectAll');
    const clearFilters = document.getElementById('clearFilters');
    const searchInput = document.querySelector('input[name="search"]');
    let debounceTimer;

    function fetchFilteredTasks(status = '', date = '', search = '') {
        const params = new URLSearchParams({status, date, search});
        fetch(`{{ route('user.tasks.ajaxFilter') }}?${params}`)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = data.rows;
                // Update filter counts
                Object.entries(data.counts).forEach(([key, val]) => {
                    const el = document.querySelector(`.filter-box[data-filter="${key}"] strong`);
                    if(el) el.textContent = val;
                });
            });
    }

    // Filter click
    filters.forEach(f => {
        f.addEventListener('click', function () {
            filters.forEach(box => box.classList.remove('active'));
            this.classList.add('active');
            fetchFilteredTasks(this.dataset.filter, dateInput.value || '', searchInput.value || '');
        });
    });

    // Date change
    dateInput.addEventListener('change', function() {
        const activeFilter = document.querySelector('.filter-box.active');
        fetchFilteredTasks(activeFilter.dataset.filter || '', this.value, searchInput.value || '');
    });

    // Search input (debounced)
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const activeFilter = document.querySelector('.filter-box.active');
            fetchFilteredTasks(activeFilter.dataset.filter || '', dateInput.value || '', this.value);
        }, 300);
    });

    // Clear filters
    clearFilters.addEventListener('click', function() {
        filters.forEach(box => box.classList.remove('active'));
        document.querySelector('.filter-box[data-filter="all"]').classList.add('active');
        searchInput.value = '';
        dateInput.value = '';
        fetchFilteredTasks('all', '', '');
    });

    // Bulk actions
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.task-checkbox').forEach(cb => cb.checked = selectAll.checked);
    });

    function getSelectedTaskIds() {
        return Array.from(document.querySelectorAll('.task-checkbox:checked')).map(cb => cb.value);
    }

    document.getElementById('bulkComplete').addEventListener('click', function() {
        const ids = getSelectedTaskIds();
        if(ids.length === 0) return alert('Select tasks first.');
        fetch('{{ route("user.tasks.bulkComplete") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ids})
        }).then(()=> location.reload());
    });

    document.getElementById('bulkDelete').addEventListener('click', function() {
        const ids = getSelectedTaskIds();
        if(ids.length === 0) return alert('Select tasks first.');
        if(!confirm('Are you sure to delete selected tasks?')) return;
        fetch('{{ route("user.tasks.bulkDelete") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({ids})
        }).then(()=> location.reload());
    });
});
</script>

@endsection
