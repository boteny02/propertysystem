@extends('layouts.app')

@section('title', 'Tenant Dashboard - PropNest')

@section('content')
<div class="space-y-6">
    <!-- Top Greeting Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-3">
                    👤 Tenant Resident Portal
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Hello, {{ auth()->user()->name }}
                </h1>
                <p class="text-slate-300 text-sm mt-1 max-w-xl">
                    Manage your tenancy, view and settle rental or utility bills via bank/crypto, and track electronic maintenance requests.
                </p>
            </div>

            <!-- Action shortcuts -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/30 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pay Bill / Rent
                </a>
                <a href="{{ route('maintenance.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Report Maintenance
                </a>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Browse Catalogue
                </a>
            </div>
        </div>
    </div>

    <!-- Active Leased Property Section -->
    @if($rentedProperty)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Current Active Lease</span>
                    <h2 class="text-xl font-bold text-slate-900 mt-0.5">{{ $rentedProperty->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $rentedProperty->location }}, {{ $rentedProperty->city }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-xs text-slate-400">Rental Rate</div>
                        <div class="text-xl font-extrabold text-indigo-600">{{ $rentedProperty->formatted_price }}</div>
                    </div>
                    <a href="{{ route('properties.show', $rentedProperty) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                        View Unit Specs
                    </a>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <div class="md:col-span-1 rounded-2xl overflow-hidden h-48 bg-slate-100">
                    <img src="{{ $rentedProperty->display_image }}" alt="{{ $rentedProperty->name }}" class="w-full h-full object-cover">
                </div>

                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] text-slate-400 block">Type</span>
                            <span class="text-xs font-bold text-slate-800">{{ $rentedProperty->property_type }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] text-slate-400 block">Bedrooms</span>
                            <span class="text-xs font-bold text-slate-800">{{ $rentedProperty->bedrooms }} Beds</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] text-slate-400 block">Bathrooms</span>
                            <span class="text-xs font-bold text-slate-800">{{ $rentedProperty->bathrooms }} Baths</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[11px] text-slate-400 block">Frequency</span>
                            <span class="text-xs font-bold text-slate-800">{{ ucfirst($rentedProperty->rent_frequency) }}</span>
                        </div>
                    </div>

                    @if($rentedProperty->facilities)
                        <div>
                            <span class="text-xs font-semibold text-slate-500 block mb-2">Amenities Included:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($rentedProperty->facilities as $fac)
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[11px] font-medium border border-indigo-100">
                                        ✓ {{ $fac }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-2 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500 border-t border-slate-100">
                        <div>
                            Landlord Contact:
                            <strong class="text-slate-800">{{ $rentedProperty->landlord->name }}</strong>
                            ({{ $rentedProperty->landlord->phone ?? $rentedProperty->landlord->email }})
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('payments.create', ['property_id' => $rentedProperty->id]) }}" class="text-emerald-600 font-bold hover:underline">
                                Pay Rent &rarr;
                            </a>
                            <span>•</span>
                            <a href="{{ route('maintenance.create', ['property_id' => $rentedProperty->id]) }}" class="text-indigo-600 font-bold hover:underline">
                                Request Repair &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl border border-dashed border-indigo-200 p-8 text-center space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">No Rental Property Assigned Yet</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto">
                You are registered as a tenant. Browse available properties in our catalogue and contact the administrator to get assigned to your unit.
            </p>
            <div>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-sm transition">
                    Explore Available Properties &rarr;
                </a>
            </div>
        </div>
    @endif

    <!-- Two Columns: Bills / Payments & Maintenance Tracker -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Column 1: Outstanding Bills & Payments -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Rental & Utility Bills</h2>
                    <p class="text-xs text-slate-500">
                        Total Outstanding: <strong class="text-rose-600 font-bold">₦{{ number_format($totalDue, 2) }}</strong>
                    </p>
                </div>
                <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
                    + Pay Bill
                </a>
            </div>

            @if($unpaidBills->isEmpty())
                <div class="p-6 text-center text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 text-sm">
                    🎉 You have zero outstanding bills. All accounts are up to date!
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($unpaidBills as $bill)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-900">{{ $bill->title }}</div>
                                <div class="text-xs text-slate-500">
                                    Type: <span class="font-medium text-slate-700">{{ $bill->bill_type }}</span>
                                    • Due: <span class="font-medium text-rose-600">{{ $bill->due_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm font-extrabold text-slate-900">₦{{ number_format($bill->amount, 2) }}</div>
                                <a href="{{ route('payments.create', ['bill_id' => $bill->id]) }}" class="mt-1 inline-block px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition">
                                    Pay Now
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Recent Payments history preview -->
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Recent Submitted Payments</span>
                    <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Full History &rarr;</a>
                </div>

                @if($recentPayments->isEmpty())
                    <p class="text-xs text-slate-400">No payment receipts submitted yet.</p>
                @else
                    <div class="space-y-2">
                        @foreach($recentPayments as $pay)
                            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div>
                                    <span class="font-bold text-slate-800">₦{{ number_format($pay->amount, 2) }}</span>
                                    <span class="text-slate-500">({{ $pay->bill_type }})</span>
                                    <div class="text-[11px] text-slate-400">{{ $pay->payment_method }} • {{ $pay->payment_date->format('M d') }}</div>
                                </div>
                                <div>
                                    @if($pay->status === 'verified')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Verified</span>
                                    @elseif($pay->status === 'pending')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pending Review</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Rejected</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Column 2: Active Maintenance Tracker & Announcements -->
        <div class="space-y-6">
            <!-- Maintenance Tracker Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Maintenance Tracker</h2>
                        <p class="text-xs text-slate-500">Live progress on reported issues</p>
                    </div>
                    <a href="{{ route('maintenance.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition">
                        + Report Issue
                    </a>
                </div>

                @if($activeMaintenance->isEmpty())
                    <div class="p-6 text-center text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-sm">
                        No active maintenance requests. Everything is operating smoothly!
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($activeMaintenance as $mReq)
                            <div class="p-3.5 rounded-xl border border-slate-200 space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('maintenance.show', $mReq) }}" class="text-sm font-bold text-slate-900 hover:text-indigo-600">
                                            {{ $mReq->title }}
                                        </a>
                                        <div class="text-xs text-slate-500">
                                            Category: <span class="font-medium text-slate-700">{{ $mReq->category }}</span>
                                            • Urgency: <span class="font-semibold {{ $mReq->urgency === 'emergency' ? 'text-rose-600' : 'text-slate-700' }}">{{ ucfirst($mReq->urgency) }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $mReq->status === 'in_progress' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $mReq->status === 'in_progress' ? 'In Progress' : 'Pending Review' }}
                                    </span>
                                </div>

                                @if($mReq->technician_notes)
                                    <div class="p-2 rounded-lg bg-blue-50/50 text-[11px] text-blue-900 border border-blue-100">
                                        <strong>Technician Note:</strong> {{ $mReq->technician_notes }}
                                    </div>
                                @endif

                                <div class="text-right">
                                    <a href="{{ route('maintenance.show', $mReq) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                        View Timeline & Details &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- System Announcements Feed Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Announcements & Notices</h2>
                    <a href="{{ route('notifications.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        All Notifications &rarr;
                    </a>
                </div>

                @if($recentAnnouncements->isEmpty())
                    <p class="text-xs text-slate-400">No recent announcements from management.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach($recentAnnouncements as $ann)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-xs font-bold text-slate-800">{{ $ann->title }}</h4>
                                    <span class="text-[10px] text-slate-400">{{ $ann->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $ann->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
