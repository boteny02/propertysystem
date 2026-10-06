@extends('layouts.app')

@section('title', 'Payment Records & Monitoring - Landlord Portal')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Payments & Audit Ledger</h1>
            <p class="text-sm text-slate-500 mt-1">
                Monitor incoming bank and cryptocurrency payments, verify transaction slips, and issue bills.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Issue New Bill
            </a>
        </div>
    </div>

    <!-- Stat Metrics with Unified Icons -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Verified Revenue</span>
                <div class="text-2xl font-black text-slate-900 mt-1">₦{{ number_format($totalCollected, 2) }}</div>
                <span class="text-[11px] text-emerald-600 font-bold mt-0.5 block">Confirmed in escrow</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Pending Review</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Awaiting confirmation</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Verified Records</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $verifiedCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Receipts approved</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Rejected Records</span>
                <div class="text-2xl font-black text-rose-600 mt-1">{{ $rejectedCount }}</div>
                <span class="text-[11px] text-slate-400 mt-0.5 block">Invalid references</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-3 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex flex-wrap items-center gap-2">
            <span class="font-bold text-slate-600 ml-1">Filter Status:</span>
            <a href="{{ route('payments.index', ['status' => 'all']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Records
            </a>
            <a href="{{ route('payments.index', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'pending' ? 'bg-amber-600 text-white font-bold shadow-xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('payments.index', ['status' => 'verified']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'verified' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                Verified ({{ $verifiedCount }})
            </a>
            <a href="{{ route('payments.index', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-xl transition {{ request('status') === 'rejected' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">
                Rejected ({{ $rejectedCount }})
            </a>
        </div>
        <div class="text-xs text-slate-400 mr-1">
            Showing {{ $payments->total() }} total transaction{{ $payments->total() === 1 ? '' : 's' }}
        </div>
    </div>

    <!-- Payments Ledger Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Submitted Tenant Payments</h2>
            <p class="text-xs text-slate-500">Inspect submitted proof images and approve or reject receipts.</p>
        </div>

        @if($payments->isEmpty())
            <div class="p-12 text-center text-slate-500 text-sm">
                No payment transactions found matching the filter criteria.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[820px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                            <th class="py-3.5 px-4 whitespace-nowrap">Tenant / Property</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Bill Type</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Amount</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Method & Reference</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Payment Date</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3.5 px-4 text-right whitespace-nowrap">Verification Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $pay)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ $pay->tenant->name }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $pay->property ? $pay->property->name : 'Unassigned Unit' }}</div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-700 whitespace-nowrap">
                                    {{ $pay->bill_type }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="text-sm font-extrabold text-slate-900">₦{{ number_format($pay->amount, 2) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-slate-800">{{ $pay->payment_method }}</div>
                                    <code class="text-[11px] text-slate-500 font-mono block max-w-[160px] truncate mt-0.5" title="{{ $pay->transaction_reference }}">
                                        {{ $pay->transaction_reference }}
                                    </code>
                                </td>
                                <td class="py-4 px-4 text-slate-600 whitespace-nowrap">
                                    {{ $pay->payment_date->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($pay->status === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            ✓ Verified
                                        </span>
                                    @elseif($pay->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                            ⌛ Pending Review
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                            ✕ Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2 whitespace-nowrap">
                                        <a href="{{ route('payments.show', $pay) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition text-xs whitespace-nowrap">
                                            Inspect Slip
                                        </a>

                                        @if($pay->status === 'pending')
                                            <form action="{{ route('admin.payments.verify', $pay) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs hover:shadow transition cursor-pointer whitespace-nowrap">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Active Tenant Bills Issued -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Recent Invoices & Bills Issued</h2>
                <p class="text-xs text-slate-500">Track outstanding bills generated across all tenant units</p>
            </div>
            <a href="{{ route('admin.bills.create') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                + Create Another Bill &rarr;
            </a>
        </div>

        @if($allBills->isEmpty())
            <p class="text-xs text-slate-400">No active bills issued yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-500 uppercase font-semibold">
                            <th class="py-2.5 px-3 whitespace-nowrap">Title</th>
                            <th class="py-2.5 px-3 whitespace-nowrap">Tenant</th>
                            <th class="py-2.5 px-3 whitespace-nowrap">Unit</th>
                            <th class="py-2.5 px-3 whitespace-nowrap">Amount</th>
                            <th class="py-2.5 px-3 whitespace-nowrap">Due Date</th>
                            <th class="py-2.5 px-3 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($allBills as $b)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-3 font-bold text-slate-800">{{ $b->title }}</td>
                                <td class="py-3 px-3 text-slate-700 whitespace-nowrap">{{ $b->tenant->name }}</td>
                                <td class="py-3 px-3 text-slate-600 whitespace-nowrap">{{ $b->property->name }}</td>
                                <td class="py-3 px-3 font-extrabold text-slate-900 whitespace-nowrap">₦{{ number_format($b->amount, 2) }}</td>
                                <td class="py-3 px-3 text-slate-600 whitespace-nowrap">{{ $b->due_date->format('M d, Y') }}</td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($b->status === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">Paid</span>
                                    @elseif($b->status === 'pending_verification')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">Verification Pending</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
