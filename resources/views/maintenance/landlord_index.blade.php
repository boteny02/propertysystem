@extends('layouts.app')

@section('title', 'Maintenance Queue & Operations - Landlord Portal')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Maintenance Tickets Queue</h1>
            <p class="text-sm text-slate-500 mt-1">
                Oversee repairs, assign contractors, manage repair status, and update residents.
            </p>
        </div>
    </div>

    <!-- Stat Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Pending Review</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">New tickets submitted</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">In Progress</span>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ $inProgressCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Technician assigned</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Resolved</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $resolvedCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Successfully completed</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Emergency Urgent</span>
            <div class="text-2xl font-black text-rose-600 mt-1">{{ $emergencyCount }}</div>
            <span class="text-[11px] text-rose-600 font-bold mt-1 block">Requires instant action</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form action="{{ route('maintenance.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2">
                <span class="font-bold text-slate-600">Status:</span>
                <select name="status" class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>

                <span class="font-bold text-slate-600 ml-2">Urgency:</span>
                <select name="urgency" class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white outline-none">
                    <option value="all">All Urgency</option>
                    <option value="emergency" {{ request('urgency') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="high" {{ request('urgency') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('urgency') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('urgency') === 'low' ? 'selected' : '' }}>Low</option>
                </select>

                <button type="submit" class="px-4 py-1.5 rounded-lg bg-indigo-600 text-white font-bold transition">
                    Filter
                </button>

                @if(request()->anyFilled(['status', 'urgency']))
                    <a href="{{ route('maintenance.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-medium">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of Tickets -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        @if($requests->isEmpty())
            <div class="p-12 text-center text-slate-500 text-sm">
                No maintenance tickets matching the current criteria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-4">Problem Title & Category</th>
                            <th class="py-3.5 px-4">Unit / Property</th>
                            <th class="py-3.5 px-4">Resident (Tenant)</th>
                            <th class="py-3.5 px-4">Urgency</th>
                            <th class="py-3.5 px-4">Scheduled Date</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($requests as $req)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900 text-sm">
                                        <a href="{{ route('maintenance.show', $req) }}" class="hover:text-indigo-600 transition">
                                            {{ $req->title }}
                                        </a>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $req->category }} • Submitted {{ $req->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-800">
                                    {{ $req->property->name }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-slate-900">{{ $req->tenant->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $req->tenant->phone ?? $req->tenant->email }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($req->urgency === 'emergency')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                            🚨 Emergency
                                        </span>
                                    @elseif($req->urgency === 'high')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                            High
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 capitalize">
                                            {{ $req->urgency }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $req->scheduled_date ? $req->scheduled_date->format('M d, Y') : 'Not scheduled' }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($req->status === 'resolved')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Resolved
                                        </span>
                                    @elseif($req->status === 'in_progress')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            🔧 In Progress
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ⌛ Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('maintenance.show', $req) }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition">
                                        Manage Ticket &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
