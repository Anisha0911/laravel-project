@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">

    <!-- ===================== HEADER ===================== -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Notifications</h2>
            <p class="text-muted small mb-0">
                View and manage your system notifications
            </p>
        </div>

        <!-- Search -->
        <!-- <form method="GET" action="{{ route('notifications.index') }}" class="w-auto">
            <div class="input-group input-group-sm shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control border-start-0"
                       placeholder="Search notifications...">
            </div>
        </form> -->
    </div>


    <!-- ===================== STATS CARDS ===================== -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                <small class="text-muted">Total</small>
                <h4 class="fw-bold mb-0">{{ $totalCount }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 bg-light">
                <small class="text-muted">Unread</small>
                <h4 class="fw-bold text-primary mb-0">{{ $unreadCount }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                <small class="text-muted">Read</small>
                <h4 class="fw-bold text-success mb-0">{{ $readCount }}</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                <small class="text-muted">Today</small>
                <h4 class="fw-bold text-warning mb-0">{{ $todayCount }}</h4>
            </div>
        </div>
    </div>


    <!-- ===================== FILTERS + ACTIONS ===================== -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <!-- Filters -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('notifications.index') }}"
               class="btn btn-sm btn-outline-secondary rounded-pill">All</a>

            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
               class="btn btn-sm btn-outline-primary rounded-pill">Unread</a>

            <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
               class="btn btn-sm btn-outline-success rounded-pill">Read</a>

            <a href="{{ route('notifications.index', ['date' => '7days']) }}"
               class="btn btn-sm btn-outline-info rounded-pill">Last 7 Days</a>

            <a href="{{ route('notifications.index', ['date' => 'month']) }}"
               class="btn btn-sm btn-outline-warning rounded-pill">Last Month</a>
        </div>

        <!-- Right Side Actions -->
        <div class="d-flex gap-2 flex-wrap">

            <!-- Mark All Read -->
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button class="btn btn-sm btn-success rounded-pill">
                    <i class="bi bi-check2-all me-1"></i> Mark All Read
                </button>
            </form>

            <!-- Delete All -->
            <button type="button"
                    class="btn btn-sm btn-danger rounded-pill"
                    data-bs-toggle="modal"
                    data-bs-target="#globalDeleteModal"
                    data-action="{{ route('notifications.deleteAll') }}">
                <i class="bi bi-trash me-1"></i> Delete All
            </button>
        </div>
    </div>


    <!-- ===================== BULK FORM START ===================== -->
    <form method="POST" action="{{ route('notifications.bulkDelete') }}">
        @csrf
        @method('DELETE')

        <!-- Bulk Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <input type="checkbox" id="selectAll">
                <label for="selectAll" class="ms-1">Select All</label>
            </div>

            <button type="submit"
                    class="btn btn-sm btn-outline-danger rounded-pill"
                    onclick="return confirm('Delete selected notifications?')">
                <i class="bi bi-trash me-1"></i> Delete Selected
            </button>
        </div>


        <!-- ===================== NOTIFICATION LIST ===================== -->
        @forelse($notifications as $notification)

            <div class="card mb-3 shadow-sm rounded-4
                {{ is_null($notification->read_at) ? 'border-start border-4 border-primary bg-light' : '' }}
                {{ ($notification->data['is_pinned'] ?? false) ? 'border-warning' : '' }}">

                <div class="card-body d-flex gap-3 align-items-start">

                    <!-- Checkbox -->
                    <div class="form-check mt-1">
                        <input class="form-check-input notification-checkbox"
                               type="checkbox"
                               name="notification_ids[]"
                               value="{{ $notification->id }}">
                    </div>

                    <!-- Content -->
                    <div class="flex-grow-1">

                        <!-- Badges -->
                        <div class="mb-1">

                            @if(($notification->data['is_pinned'] ?? false))
                                <span class="badge bg-warning text-dark rounded-pill">
                                    <i class="bi bi-pin-angle-fill me-1"></i> Pinned
                                </span>
                            @endif

                            @if(is_null($notification->read_at))
                                <span class="badge bg-primary rounded-pill">Unread</span>
                            @else
                                <span class="badge bg-success rounded-pill">Read</span>
                            @endif

                        </div>

                        <!-- Message -->
                        <div class="fw-semibold">
                            {{ $notification->data['message'] }}
                        </div>

                        <!-- Time -->
                        <div class="text-muted small mt-1">
                            {{ $notification->created_at->format('d M Y, h:i A') }}
                            • {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex gap-2 flex-wrap">

                        <!-- View -->
                        <a href="{{ route('notifications.open', $notification->id) }}"
                           class="btn btn-sm btn-primary rounded-pill">
                            <i class="bi bi-eye me-1"></i> View
                        </a>

                        <!-- Pin Toggle -->
                        <form method="POST"
                              action="{{ route('notifications.togglePin', $notification->id) }}">
                            @csrf
                            <button class="btn btn-sm
                                {{ ($notification->data['is_pinned'] ?? false) ? 'btn-warning' : 'btn-outline-warning' }}
                                rounded-pill">
                                <i class="bi bi-pin-angle me-1"></i>
                            </button>
                        </form>

                        <!-- Delete -->
                        <button type="button"
                                class="btn btn-sm btn-outline-danger rounded-pill"
                                data-bs-toggle="modal"
                                data-bs-target="#globalDeleteModal"
                                data-action="{{ route('notifications.destroy', $notification->id) }}">
                            <i class="bi bi-trash me-1"></i>
                        </button>

                    </div>

                </div>
            </div>

        @empty
            <div class="alert alert-light text-center rounded-4 shadow-sm">
                <i class="bi bi-bell-slash fs-3 mb-2"></i>
                <p class="mb-0">No notifications found.</p>
            </div>
        @endforelse

    </form>
    <!-- ===================== BULK FORM END ===================== -->


    <!-- ===================== PAGINATION ===================== -->
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>

</div>


<!-- Select All Script -->
<script>
document.getElementById('selectAll').addEventListener('change', function () {
    let checkboxes = document.querySelectorAll('.notification-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
