<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /* =====================================================
       INDEX
    ======================================================*/
    public function index(Request $request)
    {
        $user = Auth::user();

        /* -----------------------------
           BASE QUERY (Pinned First)
        ------------------------------*/
        $query = $user->notifications()
            ->orderByRaw("JSON_EXTRACT(data, '$.is_pinned') DESC")
            ->latest();

        /* -----------------------------
           SEARCH (Safe JSON Search)
        ------------------------------*/
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('data->message', 'like', "%{$search}%");
            });
        }

        /* -----------------------------
           READ / UNREAD FILTER
        ------------------------------*/
        if ($request->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        /* -----------------------------
           DATE FILTER
        ------------------------------*/
        if ($request->date === '7days') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($request->date === 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        /* -----------------------------
           PAGINATION
        ------------------------------*/
        $notifications = $query
            ->paginate(10)
            ->withQueryString();

        /* -----------------------------
           STATS
        ------------------------------*/
        $totalCount  = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();
        $readCount   = $user->readNotifications()->count();
        $todayCount  = $user->notifications()
                            ->whereDate('created_at', today())
                            ->count();

        return view('notifications.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'readCount',
            'todayCount'
        ));
    }

    /* =====================================================
       OPEN & MARK AS READ
    ======================================================*/
    public function open($id)
    {
        $notification = Auth::user()
                            ->notifications()
                            ->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $taskId = $notification->data['task_id'] ?? null;

        if (!$taskId) {
            return back();
        }

        return Auth::user()->role === 'admin'
            ? redirect('/admin/tasks/' . $taskId)
            : redirect('/user/tasks/' . $taskId);
    }

    /* =====================================================
       DELETE SINGLE
    ======================================================*/
    public function destroy($id)
    {
        $notification = Auth::user()
                            ->notifications()
                            ->findOrFail($id);

        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    /* =====================================================
       BULK DELETE
    ======================================================*/
    public function bulkDelete(Request $request)
    {
        $ids = $request->notification_ids;

        if (!empty($ids)) {
            Auth::user()
                ->notifications()
                ->whereIn('id', $ids)
                ->delete();
        }

        return back()->with('success', 'Selected notifications deleted successfully.');
    }

    /* =====================================================
       DELETE ALL
    ======================================================*/
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'All notifications deleted successfully.');
    }

    /* =====================================================
       MARK ALL AS READ
    ======================================================*/
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /* =====================================================
       TOGGLE PIN
    ======================================================*/
    public function togglePin($id)
    {
        $notification = Auth::user()
                            ->notifications()
                            ->findOrFail($id);

        $data = $notification->data ?? [];

        $data['is_pinned'] = !($data['is_pinned'] ?? false);

        $notification->update([
            'data' => $data
        ]);

        return back()->with('success', 'Notification updated successfully.');
    }
}
