@extends('layouts.app')

@section('title', 'Payment Receipt #' . $payment->id . ' - PropNest')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Top action bar -->
    <div class="flex items-center justify-between text-xs">
        <a href="{{ route('payments.index') }}" class="font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Payment Records
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-1.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-bold transition flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print / Save Receipt
            </button>
        </div>
    </div>

    <!-- Official Payment Receipt Slip Card -->
    <div id="receipt-card" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-bold flex items-center justify-center text-xl shadow-sm">
                    P
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">PropNest Official Receipt</h2>
                    <p class="text-xs text-slate-500">Property Management Escrow Services</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <div class="text-xs text-slate-400 font-mono">RECEIPT #PN-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="mt-1">
                    @if($payment->status === 'verified')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Verified & Approved
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            ⌛ Pending Landlord Verification
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            ✕ Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount Box -->
        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total Amount Settled</span>
                <span class="text-3xl sm:text-4xl font-black text-slate-900">₦{{ number_format($payment->amount, 2) }}</span>
                <span class="text-xs text-slate-400 block mt-0.5">NGN (Nigerian Naira ₦)</span>
            </div>

            <div class="text-xs space-y-1 sm:text-right text-slate-600">
                <div>Bill Category: <strong class="text-slate-900">{{ $payment->bill_type }}</strong></div>
                <div>Payment Method: <strong class="text-slate-900">{{ $payment->payment_method }}</strong></div>
                <div>Payment Date: <strong class="text-slate-900">{{ $payment->payment_date->format('F d, Y') }}</strong></div>
            </div>
        </div>

        <!-- Breakdown Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-2 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="font-bold text-slate-500 uppercase tracking-wider block">Resident / Tenant Details</span>
                <div class="text-sm font-bold text-slate-900">{{ $payment->tenant->name }}</div>
                <div class="text-slate-600">{{ $payment->tenant->email }}</div>
                @if($payment->tenant->phone)
                    <div class="text-slate-600">{{ $payment->tenant->phone }}</div>
                @endif
            </div>

            <div class="space-y-2 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                <span class="font-bold text-slate-500 uppercase tracking-wider block">Property / Unit Allocation</span>
                <div class="text-sm font-bold text-slate-900">{{ $payment->property ? $payment->property->name : 'N/A' }}</div>
                <div class="text-slate-600">{{ $payment->property ? $payment->property->location : '' }}</div>
                <div class="text-slate-600">{{ $payment->property ? $payment->property->city : '' }}</div>
            </div>
        </div>

        <!-- Transaction Reference -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1 text-xs">
            <span class="font-semibold text-slate-500 uppercase tracking-wider block">Transaction Reference / On-Chain Hash</span>
            <code class="font-mono text-slate-800 text-xs font-bold break-all block">{{ $payment->transaction_reference }}</code>
        </div>

        <!-- Remarks -->
        @if($payment->tenant_notes || $payment->admin_notes)
            <div class="space-y-3 text-xs pt-2">
                @if($payment->tenant_notes)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-700 block mb-0.5">Tenant Remarks:</strong>
                        <span class="text-slate-600">{{ $payment->tenant_notes }}</span>
                    </div>
                @endif

                @if($payment->admin_notes)
                    <div class="p-3 rounded-xl {{ $payment->status === 'verified' ? 'bg-emerald-50 text-emerald-900 border border-emerald-100' : 'bg-rose-50 text-rose-900 border border-rose-100' }}">
                        <strong class="block mb-0.5">Administrator Audit Remarks:</strong>
                        <span>{{ $payment->admin_notes }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Uploaded Proof Image / PDF -->
        @if($payment->receipt_url)
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-600">
                    Uploaded Payment Proof Slip
                </h3>
                <div class="rounded-2xl border border-slate-200 overflow-hidden max-h-96 bg-slate-100 flex items-center justify-center p-2">
                    <img src="{{ $payment->receipt_url }}" alt="Proof Slip" class="max-h-80 object-contain rounded-xl">
                </div>
            </div>
        @endif

        <!-- Verification Signature & Audit Stamp -->
        @if($payment->verified_at)
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                <div>
                    Verified by: <strong class="text-slate-700">{{ $payment->verifier ? $payment->verifier->name : 'System Administrator' }}</strong>
                </div>
                <div>
                    Timestamp: {{ $payment->verified_at->format('M d, Y H:i:s T') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Landlord Verification Action Panel (if pending) -->
    @auth
        @if(auth()->user()->isLandlord() && $payment->status === 'pending')
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900">Admin Actions: Verify or Reject Slip</h3>
                <p class="text-xs text-slate-500">
                    Cross-reference the transaction hash or bank reference with your bank statement / crypto wallet before confirming.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Approve form -->
                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="space-y-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                        @csrf
                        <span class="text-xs font-bold text-emerald-900 block">✓ Approve & Confirm Payment</span>
                        <input type="text" name="admin_notes" placeholder="Approval remarks (optional)" class="w-full px-3 py-1.5 rounded-lg border border-emerald-200 text-xs bg-white outline-none">
                        <button type="submit" class="w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition cursor-pointer">
                            Confirm & Issue Receipt
                        </button>
                    </form>

                    <!-- Reject form -->
                    <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="space-y-3 p-4 rounded-2xl bg-rose-50 border border-rose-100">
                        @csrf
                        <span class="text-xs font-bold text-rose-900 block">✕ Reject Receipt</span>
                        <input type="text" name="admin_notes" required placeholder="Reason for rejection (required)" class="w-full px-3 py-1.5 rounded-lg border border-rose-200 text-xs bg-white outline-none">
                        <button type="submit" class="w-full py-2 px-3 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer">
                            Reject Payment
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endauth
</div>
@endsection
