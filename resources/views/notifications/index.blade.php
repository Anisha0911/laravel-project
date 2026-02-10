@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-2">
        <h3>Notifications</h3>

        <!-- Filters -->
        <div class="d-flex flex-wrap gap-1">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">All</a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="btn btn-sm btn-outline-primary">Unread</a>
            <a href="{{ route('notifications.index', ['filter' => 'read']) }}" class="btn btn-sm btn-outline-success">Read</a>
            <a href="{{ route('notifications.index', ['date' => '7days']) }}" class="btn btn-sm btn-outline-info">Last 7 Days</a>
            <a href="{{ route('notifications.index', ['date' => 'month']) }}" class="btn btn-sm btn-outline-warning">Last Month</a>
        </div>
    </div>

    @forelse($notifications as $notification)
        <div class="card mb-2 shadow-sm {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">

                <div class="flex-grow-1">
                    <strong>{{ $notification->data['message'] }}</strong>
                    <div class="text-muted small">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="d-flex gap-1 flex-wrap mt-2 mt-md-0">
                    <a href="{{ route('notifications.open', $notification->id) }}" class="btn btn-sm btn-primary">View</a>

                    <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-light text-center">
            No notifications found.
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $notifications->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
