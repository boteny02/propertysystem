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

    <!-- Stat Metrics Cards with Unified Icons -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Pending Review</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">New tickets submitted</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">In Progress</span>
                <div class="text-2xl font-black text-blue-600 mt-1">{{ $inProgressCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Technician assigned</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Resolved</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $resolvedCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Successfully completed</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Emergency Urgent</span>
                <div class="text-2xl font-black text-rose-600 mt-1">{{ $emergencyCount }}</div>
                <span class="text-[11px] text-rose-600 font-bold mt-0.5 block">Requires instant action</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form action="{{ route('maintenance.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4 text-xs">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5">
                    <span class="font-bold text-slate-600">Status:</span>
                    <select name="status" class="px-3 py-1.5 rounded-xl border border-slate-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-slate-700">
                        <option value="all">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                </div>

                <div class="flex items-center gap-1.5">
                    <span class="font-bold text-slate-600">Urgency:</span>
                    <select name="urgency" class="px-3 py-1.5 rounded-xl border border-slate-300 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none text-slate-700">
                        <option value="all">All Urgency</option>
                        <option value="emergency" {{ request('urgency') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="high" {{ request('urgency') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('urgency') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('urgency') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-xs cursor-pointer">
                    Apply Filter
                </button>

                @if(request()->anyFilled(['status', 'urgency']))
                    <a href="{{ route('maintenance.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition">
                        Reset Filters
                    </a>
                @endif
            </div>

            <div class="text-xs text-slate-400">
                Showing {{ $requests->total() }} total maintenance record{{ $requests->total() === 1 ? '' : 's' }}
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
                <table class="w-full text-left text-xs min-w-[780px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-4 whitespace-nowrap">Problem Title & Category</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Unit / Property</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Resident (Tenant)</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Urgency</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Scheduled Date</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3.5 px-4 text-right whitespace-nowrap">Action</th>
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
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($req->urgency === 'emergency')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300 whitespace-nowrap">
                                            🚨 Emergency
                                        </span>
                                    @elseif($req->urgency === 'high')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200 whitespace-nowrap">
                                            High Priority
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 capitalize whitespace-nowrap">
                                            {{ $req->urgency }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">
                                    {{ $req->scheduled_date ? $req->scheduled_date->format('M d, Y') : 'Not scheduled' }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($req->status === 'resolved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            ✓ Resolved
                                        </span>
                                    @elseif($req->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                            🔧 In Progress
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                            ⌛ Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('maintenance.show', $req) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs whitespace-nowrap shadow-xs hover:shadow transition">
                                        <span>Manage Ticket</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
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
