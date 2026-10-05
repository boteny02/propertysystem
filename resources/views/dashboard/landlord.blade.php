@extends('layouts.app')

@section('title', 'Landlord & Admin Dashboard - PropNest')

@section('content')
<div class="space-y-6">
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-3">
                    👑 Landlord & Administrator Portal
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Welcome back, {{ auth()->user()->name }}
                </h1>
                <p class="text-slate-300 text-sm mt-1 max-w-xl">
                    Here is the operational overview of your property portfolio, pending payment receipts, and maintenance tickets across all units.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Property
                </a>
                <a href="{{ route('admin.bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Issue Bill
                </a>
                <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Broadcast Announcement
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Properties Stat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Properties Portfolio</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-slate-900">{{ $totalProperties }}</span>
                    <span class="text-xs text-slate-500 font-medium">units</span>
                </div>
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                    <span class="text-emerald-600 font-semibold">{{ $occupiedProperties }} Rented</span>
                    <span>•</span>
                    <span class="text-indigo-600 font-semibold">{{ $availableProperties }} Vacant</span>
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Revenue Collected Stat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Revenue</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-slate-900">₦{{ number_format($totalRevenue, 2) }}</span>
                </div>
                <p class="mt-2 text-xs text-emerald-600 font-semibold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verified in escrow
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Pending Payments Stat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Receipts</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-amber-600">{{ $pendingPaymentsCount }}</span>
                    <span class="text-xs text-slate-500 font-medium">awaiting review</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Sum: <span class="font-bold text-slate-700">₦{{ number_format($pendingPaymentsAmount, 2) }}</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Active Maintenance Stat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Maintenance Open</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-2xl font-extrabold text-rose-600">{{ $activeMaintenanceCount }}</span>
                    <span class="text-xs text-slate-500 font-medium">active tickets</span>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Requires technician review
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Two-Column Content: Payments Awaiting Review & Maintenance Queue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Section 1: Submitted Payment Receipts -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent Payment Receipts</h2>
                    <p class="text-xs text-slate-500">Tenants submitting bank or crypto proofs</p>
                </div>
                <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    View All &rarr;
                </a>
            </div>

            @if($recentPayments->isEmpty())
                <div class="p-8 text-center text-slate-500 text-sm bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    No payment records submitted yet.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentPayments as $payment)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-slate-900">₦{{ number_format($payment->amount, 2) }}</span>
                                    <span class="text-xs font-medium text-slate-500">for {{ $payment->bill_type }}</span>
                                    @if($payment->status === 'verified')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Verified</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">Pending Review</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Rejected</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500">
                                    Tenant: <span class="font-medium text-slate-800">{{ $payment->tenant->name }}</span>
                                    • Method: <span class="font-medium text-slate-700">{{ $payment->payment_method }}</span>
                                    • Date: {{ $payment->payment_date->format('M d, Y') }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('payments.show', $payment) }}" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    Inspect Slip
                                </a>

                                @if($payment->status === 'pending')
                                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Section 2: Maintenance Requests -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Active Maintenance Tickets</h2>
                    <p class="text-xs text-slate-500">Reported tenant issues needing attention</p>
                </div>
                <a href="{{ route('maintenance.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                    View All &rarr;
                </a>
            </div>

            @if($recentMaintenance->isEmpty())
                <div class="p-8 text-center text-slate-500 text-sm bg-slate-50 rounded-xl border border-dashed border-slate-200">
                    No active maintenance tickets reported.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($recentMaintenance as $req)
                        <div class="py-3.5 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <a href="{{ route('maintenance.show', $req) }}" class="text-sm font-bold text-slate-900 hover:text-indigo-600 transition">
                                        {{ $req->title }}
                                    </a>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        Unit: <span class="font-medium text-slate-700">{{ $req->property->name }}</span>
                                        • Tenant: <span class="font-medium text-slate-700">{{ $req->tenant->name }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-1.5 shrink-0">
                                    @if($req->urgency === 'emergency')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">🚨 Emergency</span>
                                    @elseif($req->urgency === 'high')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-800 border border-orange-200">High</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">{{ ucfirst($req->urgency) }}</span>
                                    @endif

                                    @if($req->status === 'resolved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Resolved</span>
                                    @elseif($req->status === 'in_progress')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">In Progress</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs text-slate-500 pt-1">
                                <span>Category: <strong class="text-slate-700">{{ $req->category }}</strong></span>
                                <a href="{{ route('maintenance.show', $req) }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
                                    Manage Status &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Section 3: Property Units Overview -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Catalogue Properties Overview</h2>
                <p class="text-xs text-slate-500">Quick status of your units and tenant tenancy</p>
            </div>
            <a href="{{ route('properties.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                Browse Full Catalogue &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($recentProperties as $prop)
                <div class="rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="h-32 w-full bg-slate-100 relative">
                            <img src="{{ $prop->display_image }}" alt="{{ $prop->name }}" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                @if($prop->status === 'available')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white shadow-xs">Available</span>
                                @elseif($prop->status === 'rented')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-600 text-white shadow-xs">Occupied</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white shadow-xs">Maintenance</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-3.5">
                            <h3 class="text-xs font-bold text-slate-900 truncate">{{ $prop->name }}</h3>
                            <p class="text-[11px] text-slate-500 truncate">{{ $prop->location }}</p>
                            <div class="mt-2 text-xs font-extrabold text-indigo-600">
                                {{ $prop->formatted_price }}
                            </div>
                            <div class="mt-1 text-[11px] text-slate-500">
                                Tenant: <span class="font-medium text-slate-800">{{ $prop->currentTenant ? $prop->currentTenant->name : 'None (Vacant)' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 pt-0 border-t border-slate-100 flex items-center justify-between text-xs">
                        <a href="{{ route('admin.properties.edit', $prop) }}" class="font-semibold text-slate-600 hover:text-indigo-600">
                            Edit Specs
                        </a>
                        <a href="{{ route('properties.show', $prop) }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
                            View Page &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
