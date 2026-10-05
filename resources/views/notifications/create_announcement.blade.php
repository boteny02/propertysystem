@extends('layouts.app')

@section('title', 'Post Broadcast Announcement - Landlord Portal')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Post Community Announcement</h1>
            <p class="text-sm text-slate-500">Send an instant notification update or emergency notice to residents.</p>
        </div>
        <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Notifications
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="target" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Target Recipients *
                </label>
                <select name="target" id="target" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                    <option value="all_tenants">All Active Tenants Only</option>
                    <option value="all_users">All Registered Platform Users</option>
                </select>
            </div>

            <div>
                <label for="title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Announcement Headline / Subject *
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    required
                    placeholder="e.g. Notice: Routine Elevator Maintenance This Saturday"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <div>
                <label for="message" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Announcement Body Message *
                </label>
                <textarea
                    name="message"
                    id="message"
                    rows="5"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    placeholder="Write the details of the notice, dates, instructions, or emergency protocols..."
                >{{ old('message') }}</textarea>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
            >
                Broadcast Announcement to Residents
            </button>
        </form>
    </div>
</div>
@endsection
