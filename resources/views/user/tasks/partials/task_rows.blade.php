@foreach($tasks as $task)
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
    <td><input type="checkbox" class="task-checkbox" value="{{ $task->id }}"></td>
    <td>
        <strong>{{ $task->title }}</strong><br>
        <small class="text-muted">{{ $task->project->name ?? '' }}</small>
    </td>
    <td>{{ $task->created_at->format('d M Y') }}</td>
    <td><span class="badge bg-{{ $statusColor }}"><i class="bi {{ $statusIcon }}"></i>{{ ucfirst(str_replace('_',' ',$task->status)) }}</span></td>
    <td><span class="badge bg-{{ $priorityColor }}"><i class="bi {{ $priorityIcon }}"></i>{{ ucfirst($task->priority) }}</span></td>

    <td>{{ $task->due_date ?? 'N/A' }}</td>
    <td class="text-center">
        <a href="{{ route('user.tasks.show',$task) }}" class="btn btn-sm btn-primary">View</a>
    </td>
</tr>
@endforeach
