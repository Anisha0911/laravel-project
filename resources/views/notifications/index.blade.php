<h3>Notifications</h3>

@foreach(auth()->user()->notifications as $notification)
    @php
        $taskId = $notification->data['task_id'];
        $routeUrl = auth()->user()->role === 'admin' 
            ? url('/admin/tasks/'.$taskId)   // Admin URL
            : url('/user/tasks/'.$taskId);   // User URL
    @endphp

    <div style="border:1px solid #ccc;padding:10px;margin-bottom:10px">
        {{ $notification->data['message'] }}
        <br>
        <a href="{{ $routeUrl }}">
            View Task
        </a>
    </div>
@endforeach
