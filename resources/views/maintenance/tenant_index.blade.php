@extends('layouts.app')

@section('title', 'Maintenance Requests - PropNest')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Maintenance & Repairs</h1>
            <p class="text-sm text-slate-500 mt-1">
                Report property defects, plumbing, or electrical issues directly to management.
            </p>
        </div>

        <div>
            <a href="{{ route('maintenance.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Report Problem / Repair
            </a>
        </div>
    </div>

    @if($requests->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No Maintenance Tickets on Record</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                If something breaks or needs inspection in your unit, submit an electronic request and our technicians will assist.
            </p>
            <div class="pt-2">
                <a href="{{ route('maintenance.create') }}" class="inline-block px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                    Submit New Request
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($requests as $req)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3 flex flex-col justify-between hover:shadow-md transition">
                    <div class="space-y-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                                    {{ $req->category }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 mt-0.5">
                                    <a href="{{ route('maintenance.show', $req) }}" class="hover:text-indigo-600 transition">
                                        {{ $req->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Unit: {{ $req->property->name }} • Submitted {{ $req->created_at->format('M d, Y') }}
                                </p>
                            </div>

                            <div class="flex flex-col items-end gap-1.5 shrink-0">
                                @if($req->urgency === 'emergency')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        🚨 Emergency
                                    </span>
                                @elseif($req->urgency === 'high')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                        High Urgency
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                                        {{ $req->urgency }}
                                    </span>
                                @endif

                                @if($req->status === 'resolved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ Resolved
                                    </span>
                                @elseif($req->status === 'in_progress')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        🔧 In Progress
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        ⌛ Pending Review
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $req->description }}
                        </p>

                        @if($req->technician_notes)
                            <div class="p-2.5 rounded-xl bg-blue-50/60 border border-blue-100 text-xs text-blue-950">
                                <strong class="text-blue-900 block text-[11px] uppercase tracking-wider">Technician Note:</strong>
                                {{ $req->technician_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">
                            @if($req->scheduled_date)
                                Scheduled: <strong class="text-slate-700">{{ $req->scheduled_date->format('M d, Y') }}</strong>
                            @else
                                Awaiting technician dispatch
                            @endif
                        </span>
                        <a href="{{ route('maintenance.show', $req) }}" class="font-bold text-indigo-600 hover:text-indigo-800">
                            View Ticket Details &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
