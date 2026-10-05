<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = SystemNotification::where('user_id', $user->id);

        if ($request->input('filter') === 'unread') {
            $query->whereNull('read_at');
        } elseif ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = SystemNotification::where('user_id', $user->id)->whereNull('read_at')->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(SystemNotification $notification): RedirectResponse
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        SystemNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function createAnnouncement(): View
    {
        if (! auth()->user()->isLandlord()) {
            abort(403);
        }

        return view('notifications.create_announcement');
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        if (! auth()->user()->isLandlord()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'target' => ['required', 'in:all_tenants,all_users'],
        ]);

        $recipientsQuery = User::query();
        if ($validated['target'] === 'all_tenants') {
            $recipientsQuery->where('role', 'tenant');
        }

        $recipients = $recipientsQuery->get();

        foreach ($recipients as $recipient) {
            SystemNotification::create([
                'user_id' => $recipient->id,
                'sender_id' => $request->user()->id,
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => 'announcement',
                'link' => route('notifications.index'),
            ]);
        }

        return redirect()->route('notifications.index')
            ->with('success', "Announcement broadcasted to {$recipients->count()} user(s)!");
    }
}
