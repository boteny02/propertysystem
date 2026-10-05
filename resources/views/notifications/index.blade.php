@extends('layouts.app')

@section('title', 'Notifications & System Updates - PropNest')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">System Updates & Notices</h1>
            <p class="text-sm text-slate-500 mt-1">
                Stay updated with rent receipts, payment approvals, maintenance schedules, and building announcements.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                        Mark All as Read
                    </button>
                </form>
            @endif

            @if(auth()->user()->isLandlord())
                <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Post Announcement
                </a>
            @endif
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3 text-xs overflow-x-auto">
        <a href="{{ route('notifications.index') }}" class="px-3 py-1.5 rounded-lg {{ !request('filter') && !request('type') ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            All Notifications
        </a>
        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded-lg {{ request('filter') === 'unread' ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            Unread ({{ $unreadCount }})
        </a>
        <a href="{{ route('notifications.index', ['type' => 'payment']) }}" class="px-3 py-1.5 rounded-lg {{ request('type') === 'payment' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
            Payment Updates
        </a>
        <a href="{{ route('notifications.index', ['type' => 'maintenance']) }}" class="px-3 py-1.5 rounded-lg {{ request('type') === 'maintenance' ? 'bg-blue-600 text-white font-bold' : 'bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200' }}">
            Maintenance
        </a>
        <a href="{{ route('notifications.index', ['type' => 'announcement']) }}" class="px-3 py-1.5 rounded-lg {{ request('type') === 'announcement' ? 'bg-amber-600 text-white font-bold' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
            Announcements
        </a>
    </div>

    <!-- Notifications Feed -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        @if($notifications->isEmpty())
            <div class="p-12 text-center text-slate-500 text-sm">
                No notifications found in this category.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($notifications as $notif)
                    <div class="p-5 flex items-start gap-4 hover:bg-slate-50/70 transition {{ $notif->isRead() ? 'opacity-75' : 'bg-indigo-50/20' }}">
                        <!-- Icon based on type -->
                        <div class="shrink-0 mt-0.5">
                            @if($notif->type === 'payment')
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            @elseif($notif->type === 'maintenance')
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    </svg>
                                </div>
                            @elseif($notif->type === 'announcement')
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    {{ $notif->title }}
                                    @if(! $notif->isRead())
                                        <span class="w-2 h-2 rounded-full bg-indigo-600 inline-block" title="Unread"></span>
                                    @endif
                                </h3>
                                <span class="text-[11px] text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed">{{ $notif->message }}</p>

                            <div class="pt-1 flex items-center gap-3 text-xs">
                                @if($notif->link)
                                    <form action="{{ route('notifications.read', $notif) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="font-bold text-indigo-600 hover:text-indigo-800 transition cursor-pointer">
                                            Open Related Record &rarr;
                                        </button>
                                    </form>
                                @endif

                                @if(! $notif->isRead() && ! $notif->link)
                                    <form action="{{ route('notifications.read', $notif) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
