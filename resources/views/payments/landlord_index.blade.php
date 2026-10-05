@extends('layouts.app')

@section('title', 'Payment Records & Monitoring - Landlord Portal')

@section('content')
<div class="space-y-8">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Payments & Audit Ledger</h1>
            <p class="text-sm text-slate-500 mt-1">
                Monitor incoming bank and cryptocurrency payments, verify transaction slips, and issue bills.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.bills.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Issue New Bill
            </a>
        </div>
    </div>

    <!-- Stat Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total Verified Revenue</span>
            <div class="text-2xl font-black text-slate-900 mt-1">₦{{ number_format($totalCollected, 2) }}</div>
            <span class="text-[11px] text-emerald-600 font-bold mt-1 block">Confirmed in escrow</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Pending Review</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Awaiting confirmation</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Verified Records</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $verifiedCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Receipts approved</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Rejected Records</span>
            <div class="text-2xl font-black text-rose-600 mt-1">{{ $rejectedCount }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Invalid references</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-700">Filter Status:</span>
            <a href="{{ route('payments.index', ['status' => 'all']) }}" class="px-3 py-1.5 rounded-lg {{ !request('status') || request('status') === 'all' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Records
            </a>
            <a href="{{ route('payments.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'pending' ? 'bg-amber-600 text-white font-bold' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                Pending ({{ $pendingCount }})
            </a>
            <a href="{{ route('payments.index', ['status' => 'verified']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'verified' ? 'bg-emerald-600 text-white font-bold' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                Verified ({{ $verifiedCount }})
            </a>
            <a href="{{ route('payments.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'rejected' ? 'bg-rose-600 text-white font-bold' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">
                Rejected ({{ $rejectedCount }})
            </a>
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
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Tenant / Property</th>
                            <th class="py-3 px-4">Bill Type</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Method & Reference</th>
                            <th class="py-3 px-4">Payment Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Verification Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $pay)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-900">{{ $pay->tenant->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $pay->property ? $pay->property->name : 'Unassigned Unit' }}</div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-700">
                                    {{ $pay->bill_type }}
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-extrabold text-slate-900">₦{{ number_format($pay->amount, 2) }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-slate-800">{{ $pay->payment_method }}</div>
                                    <code class="text-[10px] text-slate-500 font-mono block max-w-[140px] truncate" title="{{ $pay->transaction_reference }}">
                                        {{ $pay->transaction_reference }}
                                    </code>
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $pay->payment_date->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($pay->status === 'verified')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ✓ Verified
                                        </span>
                                    @elseif($pay->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                            ⌛ Pending Review
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ✕ Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('payments.show', $pay) }}" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50">
                                            Inspect Slip
                                        </a>

                                        @if($pay->status === 'pending')
                                            <form action="{{ route('admin.payments.verify', $pay) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-xs">
                                                    Approve
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
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase font-semibold">
                            <th class="py-2.5 px-3">Title</th>
                            <th class="py-2.5 px-3">Tenant</th>
                            <th class="py-2.5 px-3">Unit</th>
                            <th class="py-2.5 px-3">Amount</th>
                            <th class="py-2.5 px-3">Due Date</th>
                            <th class="py-2.5 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($allBills as $b)
                            <tr>
                                <td class="py-3 px-3 font-bold text-slate-800">{{ $b->title }}</td>
                                <td class="py-3 px-3 text-slate-700">{{ $b->tenant->name }}</td>
                                <td class="py-3 px-3 text-slate-600">{{ $b->property->name }}</td>
                                <td class="py-3 px-3 font-extrabold text-slate-900">₦{{ number_format($b->amount, 2) }}</td>
                                <td class="py-3 px-3 text-slate-600">{{ $b->due_date->format('M d, Y') }}</td>
                                <td class="py-3 px-3">
                                    @if($b->status === 'paid')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">Paid</span>
                                    @elseif($b->status === 'pending_verification')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">Verification Pending</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700">Unpaid</span>
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
