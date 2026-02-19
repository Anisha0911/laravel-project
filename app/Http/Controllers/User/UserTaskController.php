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
    $query = Task::where('user_id', auth()->id());

    // ---------- STATUS FILTER ----------
    if ($request->status && $request->status !== 'all') {
        switch ($request->status) {
            case 'overdue':
                $query->whereDate('due_date', '<', now())
                      ->where('status', '!=', 'completed');
                break;

            case 'high_priority':
                $query->whereIn('priority', ['high', 'urgent']);
                break;

            default:
                $query->where('status', $request->status);
        }
    }

    // ---------- SEARCH ----------
    if ($request->search) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // ---------- DATE FILTER ----------
    if ($request->date) {
        $query->whereDate('due_date', $request->date);
    }

    // ---------- SORT ----------
    if ($request->sort) {
        switch ($request->sort) {
            case 'latest':
                $query->latest();
                break;
            case 'due_asc':
                $query->orderBy('due_date', 'asc');
                break;
            case 'due_desc':
                $query->orderBy('due_date', 'desc');
                break;
            case 'priority':
                $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')");
                break;
        }
    } else {
        $query->latest();
    }

    $tasks = $query->get();

    // If no tasks found, generate a "no tasks" row
if ($tasks->isEmpty()) {
    $rows = '<tr>
        <td colspan="7" class="text-center text-muted py-4">
            <i class="bi bi-folder-x fs-4"></i><br>
            No ' . ucfirst(str_replace('_',' ',$request->status ?: 'tasks')) . ' found.
        </td>
    </tr>';
} else {
    $rows = view('user.tasks.partials.task_rows', compact('tasks'))->render();
}

    // ---------- RETURN ROWS ----------
    $rows = view('user.tasks.partials.task_rows', compact('tasks'))->render();

    // ---------- COUNT FOR FILTER CARDS ----------
    $counts = [
        'all' => Task::where('user_id', auth()->id())->count(),
        'completed' => Task::where('user_id', auth()->id())->where('status','completed')->count(),
        'in_progress' => Task::where('user_id', auth()->id())->where('status','in_progress')->count(),
        'hold' => Task::where('user_id', auth()->id())->where('status','hold')->count(),
        'pending' => Task::where('user_id', auth()->id())->where('status','pending')->count(),
        'overdue' => Task::where('user_id', auth()->id())
                          ->whereDate('due_date', '<', now())
                          ->where('status', '!=', 'completed')->count(),
        'high_priority' => Task::where('user_id', auth()->id())
                               ->whereIn('priority', ['high','urgent'])->count(),
    ];

    return response()->json([
        'rows' => $rows,
        'counts' => $counts
    ]);
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
        $request->validate([
            'status' => 'required|in:pending,in_progress,review,hold,completed'
        ]);
        $task->update([
            'status' => $request->status
        ]);

        return back();
    }

    public function bulkComplete(Request $request)
    {
        $taskIds = $request->input('ids', []);
        if(!empty($taskIds)){
            Task::whereIn('id', $taskIds)
                ->where('user_id', auth()->id())
                ->update(['status' => 'completed']);
        }
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $taskIds = $request->input('ids', []);
        if(!empty($taskIds)){
            Task::whereIn('id', $taskIds)
                ->where('user_id', auth()->id())
                ->delete();
        }
        return response()->json(['success' => true]);
    }
}
