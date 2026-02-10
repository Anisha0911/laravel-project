<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserTaskController extends Controller
{   
    // ✅ List all tasks assigned to the logged-in user
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())->get();

        // Status Counts
        $completedCount  = $tasks->where('status', 'completed')->count();
        $inProgressCount = $tasks->where('status', 'in_progress')->count();
        $pendingCount    = $tasks->where('status', 'pending')->count();
        $holdCount       = $tasks->where('status', 'hold')->count();

        // Date logic
        $today = now()->toDateString();

        $inTimeCount = $tasks->where('due_date', '>=', $today)->where('status', '!=','completed')->count();
        $delayedCount = $tasks->where('due_date', '<', $today)->where('status', '!=', 'completed')->count();

        return view('user.tasks.index', compact(
            'tasks',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'holdCount',
            'inTimeCount',
            'delayedCount'
        ));
    }

    // ✅ AJAX Filter for tasks
public function ajaxFilter(Request $request)
{
    $status = $request->query('status'); 
    $date   = $request->query('date');   

    $query = Task::where('user_id', auth()->id());

    $today = now()->toDateString();

    // Apply status filter ONLY if it's a real status
    if ($status && $status !== 'all') {
        switch ($status) {
            case 'in_time':
                $query->where('due_date', '>=', $today)
                      ->where('status', '!=', 'completed');
                break;

            case 'delayed':
                $query->where('due_date', '<', $today)
                      ->where('status', '!=', 'completed');
                break;

            default:
                $query->where('status', $status);
        }
    }


        // Only filter by status if it's not empty and not "all"
    if ($status && $status !== 'all') {
        switch ($status) {
            case 'in_time':
                $query->where('due_date', '>=', $today)
                      ->where('status', '!=', 'completed');
                break;

            case 'delayed':
                $query->where('due_date', '<', $today)
                      ->where('status', '!=', 'completed');
                break;

            default:
                $query->where('status', $status);
        }
    }

    

    // Apply date filter (works for all statuses including All)
    if ($date) {
        $query->whereDate('created_at', $date);
    }

    $tasks = $query->latest()->get();

    // Generate HTML table rows
    $html = '';
    foreach ($tasks as $task) {
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

        $html .= "
        <tr>
            <td class='fw-semibold'>
                {$task->title}
                <div class='small text-muted'>".($task->project->name ?? '')."</div>
            </td>
            <td class='text-muted'>".$task->created_at->format('d M Y')."</td>
            <td>
                <span class='badge bg-{$statusColor}'>
                    <i class='bi {$statusIcon}'></i>
                    ".ucfirst(str_replace('_',' ',$task->status))."
                </span>
            </td>
            <td>
                <span class='badge bg-{$priorityColor}'>
                    <i class='bi {$priorityIcon}'></i>
                    ".ucfirst($task->priority)."
                </span>
            </td>
            <td class='text-muted'>".($task->due_date ? $task->due_date->format('d M Y') : 'N/A')."</td>
            <td class='text-center'>
                <a href='".route('user.tasks.show',$task)."' class='btn btn-sm btn-primary'>View</a>
            </td>
        </tr>";
    }

    if ($html === '') {
        $html = "<tr><td colspan='6' class='text-center text-muted py-4'>No tasks found.</td></tr>";
    }

    // Update counts for all filters
    $allTasks = Task::where('user_id', auth()->id())->get();

    $counts = [
        'completed'    => $allTasks->where('status', 'completed')->count(),
        'in_progress'  => $allTasks->where('status', 'in_progress')->count(),
        'hold'         => $allTasks->where('status', 'hold')->count(),
        'pending'      => $allTasks->where('status', 'pending')->count(),
        'in_time'      => $allTasks->where('due_date', '>=', $today)->where('status', '!=', 'completed')->count(),
        'delayed'      => $allTasks->where('due_date', '<', $today)->where('status', '!=', 'completed')->count(),
        'all'          => $allTasks->count(),
    ];

    return response()->json(['rows' => $html, 'counts' => $counts]);
}



    // ✅ Show single task
    public function show(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        return view('user.tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
{
    abort_if($task->user_id !== auth()->id(), 403);

    $task->update([
        'status' => $request->status
    ]);

    return back();
}


}
