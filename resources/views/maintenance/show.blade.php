@extends('layouts.app')

@section('title', 'Maintenance Ticket #' . $maintenance->id . ' - PropNest')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between text-xs">
        <a href="{{ route('maintenance.index') }}" class="font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Maintenance Tickets
        </a>
        <span class="text-slate-400 font-mono">TICKET #MT-{{ str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>

    <!-- Main Ticket Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $maintenance->category }}
                    </span>

                    @if($maintenance->urgency === 'emergency')
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                            🚨 Emergency Priority
                        </span>
                    @elseif($maintenance->urgency === 'high')
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                            High Priority
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 capitalize">
                            {{ $maintenance->urgency }} Priority
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $maintenance->title }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Property: <strong class="text-slate-800">{{ $maintenance->property->name }}</strong>
                    • Resident: <strong class="text-slate-800">{{ $maintenance->tenant->name }}</strong>
                    • Reported: {{ $maintenance->created_at->format('F d, Y \a\t g:i A') }}
                </p>
            </div>

            <div class="text-left sm:text-right">
                @if($maintenance->status === 'resolved')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ✓ Resolved
                    </span>
                @elseif($maintenance->status === 'in_progress')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        🔧 In Progress
                    </span>
                @elseif($maintenance->status === 'cancelled')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        ✕ Cancelled
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                        ⌛ Pending Review
                    </span>
                @endif
            </div>
        </div>

        <!-- Progress Timeline Tracker -->
        <div class="py-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Request Progress Tracker</h3>
            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                <!-- Step 1: Submitted -->
                <div class="p-3 rounded-2xl bg-indigo-50 border border-indigo-200">
                    <div class="w-6 h-6 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center mx-auto text-[11px] mb-1">✓</div>
                    <span class="font-bold text-slate-900 block">Submitted</span>
                    <span class="text-[10px] text-slate-500">{{ $maintenance->created_at->format('M d') }}</span>
                </div>

                <!-- Step 2: In Progress -->
                <div class="p-3 rounded-2xl {{ in_array($maintenance->status, ['in_progress', 'resolved']) ? 'bg-blue-50 border border-blue-200' : 'bg-slate-50 border border-slate-200 opacity-60' }}">
                    <div class="w-6 h-6 rounded-full {{ in_array($maintenance->status, ['in_progress', 'resolved']) ? 'bg-blue-600 text-white' : 'bg-slate-300 text-slate-600' }} font-bold flex items-center justify-center mx-auto text-[11px] mb-1">
                        {{ in_array($maintenance->status, ['in_progress', 'resolved']) ? '✓' : '2' }}
                    </div>
                    <span class="font-bold text-slate-900 block">Technician Dispatched</span>
                    <span class="text-[10px] text-slate-500">
                        {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('M d') : 'Pending schedule' }}
                    </span>
                </div>

                <!-- Step 3: Resolved -->
                <div class="p-3 rounded-2xl {{ $maintenance->status === 'resolved' ? 'bg-emerald-50 border border-emerald-200' : 'bg-slate-50 border border-slate-200 opacity-60' }}">
                    <div class="w-6 h-6 rounded-full {{ $maintenance->status === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-600' }} font-bold flex items-center justify-center mx-auto text-[11px] mb-1">
                        {{ $maintenance->status === 'resolved' ? '✓' : '3' }}
                    </div>
                    <span class="font-bold text-slate-900 block">Resolved</span>
                    <span class="text-[10px] text-slate-500">
                        {{ $maintenance->resolved_at ? $maintenance->resolved_at->format('M d') : 'Pending completion' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Problem Description -->
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Reported Problem Description</h3>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $maintenance->description }}
            </div>
        </div>

        <!-- Attached Photo -->
        @if($maintenance->photo_url)
            <div class="space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Attached Photo of Defect</h3>
                <div class="rounded-2xl border border-slate-200 overflow-hidden max-h-96 bg-slate-100 flex items-center justify-center p-2">
                    <img src="{{ $maintenance->photo_url }}" alt="Defect Photo" class="max-h-80 object-contain rounded-xl">
                </div>
            </div>
        @endif

        <!-- Technician / Resolution Notes -->
        @if($maintenance->technician_notes)
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 space-y-1 text-xs">
                <span class="font-bold text-blue-900 uppercase tracking-wider block">Technician & Resolution Notes:</span>
                <p class="text-blue-950 text-sm leading-relaxed">{{ $maintenance->technician_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Landlord Management Status Panel -->
    @auth
        @if(auth()->user()->isLandlord())
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
                <h3 class="text-base font-bold text-slate-900">Landlord Operations: Update Ticket Status</h3>
                <p class="text-xs text-slate-500">Update status, book an inspection date, or document contractor resolution notes.</p>

                <form action="{{ route('admin.maintenance.updateStatus', $maintenance) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Progress Status *
                            </label>
                            <select name="status" id="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                                <option value="pending" {{ $maintenance->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="in_progress" {{ $maintenance->status === 'in_progress' ? 'selected' : '' }}>In Progress (Technician Dispatched)</option>
                                <option value="resolved" {{ $maintenance->status === 'resolved' ? 'selected' : '' }}>Resolved & Completed</option>
                                <option value="cancelled" {{ $maintenance->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="scheduled_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Scheduled Inspection Date
                            </label>
                            <input
                                type="date"
                                name="scheduled_date"
                                id="scheduled_date"
                                value="{{ old('scheduled_date', $maintenance->scheduled_date ? $maintenance->scheduled_date->format('Y-m-d') : '') }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="technician_notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Technician / Resolution Notes
                        </label>
                        <textarea
                            name="technician_notes"
                            id="technician_notes"
                            rows="3"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                            placeholder="e.g. Plumber assigned for Wednesday 2 PM. Replacement parts ordered..."
                        >{{ old('technician_notes', $maintenance->technician_notes) }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
                    >
                        Save Status & Notify Resident
                    </button>
                </form>
            </div>
        @endif
    @endauth
</div>
@endsection
