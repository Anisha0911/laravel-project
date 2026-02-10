<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->notifications()->latest(); // latest first

        // Read / unread filter
        if ($request->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Date filter
        if ($request->date === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($request->date === 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        // Paginate 10 per page, keep query string
        $notifications = $query->paginate(10)->withQueryString();

        return view('notifications.index', compact('notifications'));
    }

    // Open and mark as read
    public function open($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $taskId = $notification->data['task_id'];
        return auth()->user()->role === 'admin'
            ? redirect('/admin/tasks/' . $taskId)
            : redirect('/user/tasks/' . $taskId);
    }

    // Delete notification
    public function destroy($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notification deleted');
    }
}
