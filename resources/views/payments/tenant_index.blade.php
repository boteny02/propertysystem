@extends('layouts.app')

@section('title', 'Rent & Payments Management - PropNest')

@section('content')
<div class="space-y-8">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Payments & Invoicing</h1>
            <p class="text-sm text-slate-500 mt-1">
                View your outstanding bills, access official payment channels, and submit payment proofs.
            </p>
        </div>

        <div>
            <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Submit Payment Receipt
            </a>
        </div>
    </div>

    <!-- Official Payment Information Channels (Bank Transfer & Cryptocurrency) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-2">
                🏛 Official Payment Channels
            </div>
            <h2 class="text-xl font-bold text-slate-900">Payment Accounts & Transfer Details</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Make your transfer to either our designated bank account or our verified cryptocurrency wallets, then submit your receipt below.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Channel 1: Bank Transfer Details -->
            <div class="p-6 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-white space-y-4 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Bank Wire / Electronic Transfer</span>
                    </div>
                    <span class="text-[11px] px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-semibold">Active Escrow</span>
                </div>

                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-white/10">
                        <span class="text-slate-400">Beneficiary Bank:</span>
                        <strong class="text-white">{{ $bankDetails['bank_name'] }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-white/10">
                        <span class="text-slate-400">Account Name:</span>
                        <strong class="text-white">{{ $bankDetails['account_name'] }}</strong>
                    </div>
                    <div class="flex items-center justify-between pb-2 border-b border-white/10">
                        <span class="text-slate-400">Account Number:</span>
                        <div class="flex items-center gap-2">
                            <code class="text-amber-300 font-mono text-sm font-bold">{{ $bankDetails['account_number'] }}</code>
                            <button type="button" onclick="copyToClipboard('{{ $bankDetails['account_number'] }}', this)" data-copied-text="✓ Copied" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 text-xs transition cursor-pointer flex items-center gap-1" title="Copy Account Number">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pb-1">
                        <span class="text-slate-400">SWIFT / Routing:</span>
                        <code class="text-slate-200 font-mono">{{ $bankDetails['swift_code'] }}</code>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-300 leading-relaxed">
                    💡 <strong>Notice:</strong> {{ $bankDetails['instructions'] }}
                </div>
            </div>

            <!-- Channel 2: Cryptocurrency Payment Wallets -->
            <div class="p-6 rounded-2xl bg-gradient-to-br from-indigo-950 to-slate-900 text-white space-y-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">Cryptocurrency Payment Facility</span>
                    </div>
                    <span class="text-[11px] px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold">Instant On-Chain</span>
                </div>

                <!-- Crypto Wallets List -->
                <div class="space-y-2.5 pt-1 text-xs">
                    <!-- USDT TRC-20 -->
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-emerald-300">{{ $cryptoDetails['usdt_trc20']['name'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $cryptoDetails['usdt_trc20']['network'] }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <code class="text-[11px] text-slate-200 font-mono break-all">{{ $cryptoDetails['usdt_trc20']['address'] }}</code>
                            <button type="button" onclick="copyToClipboard('{{ $cryptoDetails['usdt_trc20']['address'] }}', this)" data-copied-text="✓ Copied" class="px-2 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 text-xs shrink-0 transition cursor-pointer flex items-center gap-1" title="Copy Address">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>

                    <!-- Bitcoin -->
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-amber-300">{{ $cryptoDetails['bitcoin']['name'] }}</span>
                            <span class="text-[10px] text-slate-400">{{ $cryptoDetails['bitcoin']['network'] }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <code class="text-[11px] text-slate-200 font-mono break-all">{{ $cryptoDetails['bitcoin']['address'] }}</code>
                            <button type="button" onclick="copyToClipboard('{{ $cryptoDetails['bitcoin']['address'] }}', this)" data-copied-text="✓ Copied" class="px-2 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 text-xs shrink-0 transition cursor-pointer flex items-center gap-1" title="Copy Address">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>

                    <!-- Ethereum & USDT ERC20 -->
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-indigo-300">Ethereum / USDT (ERC-20)</span>
                            <span class="text-[10px] text-slate-400">Ethereum Network</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <code class="text-[11px] text-slate-200 font-mono break-all">{{ $cryptoDetails['ethereum']['address'] }}</code>
                            <button type="button" onclick="copyToClipboard('{{ $cryptoDetails['ethereum']['address'] }}', this)" data-copied-text="✓ Copied" class="px-2 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-slate-300 text-xs shrink-0 transition cursor-pointer flex items-center gap-1" title="Copy Address">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding Bills Section -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Your Pending Bills</h2>
                <p class="text-xs text-slate-500">Select any bill to upload payment confirmation and settle</p>
            </div>
        </div>

        @if($unpaidBills->isEmpty())
            <div class="p-6 text-center text-emerald-700 bg-emerald-50 rounded-2xl border border-emerald-200 text-sm">
                🎉 No unpaid bills found. All your rental, electricity, and maintenance accounts are current.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[650px]">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider bg-slate-50/50">
                            <th class="py-3 px-4 whitespace-nowrap">Bill Title</th>
                            <th class="py-3 px-4 whitespace-nowrap">Type</th>
                            <th class="py-3 px-4 whitespace-nowrap">Amount</th>
                            <th class="py-3 px-4 whitespace-nowrap">Due Date</th>
                            <th class="py-3 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 text-right whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($unpaidBills as $bill)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $bill->title }}</td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium whitespace-nowrap">{{ $bill->bill_type }}</td>
                                <td class="py-3.5 px-4 font-extrabold text-slate-900 text-sm whitespace-nowrap">₦{{ number_format($bill->amount, 2) }}</td>
                                <td class="py-3.5 px-4 text-rose-600 font-semibold whitespace-nowrap">{{ $bill->due_date->format('M d, Y') }}</td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($bill->status === 'pending_verification')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">
                                            Awaiting Verification
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 whitespace-nowrap">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('payments.create', ['bill_id' => $bill->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition text-xs whitespace-nowrap shadow-xs">
                                        <span>Pay This Bill</span>
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
        @endif
    </div>

    <!-- Submitted Payment Receipts History Ledger -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Submitted Payment History</h2>
            <p class="text-xs text-slate-500">Full audit trail of receipts submitted and landlord verification</p>
        </div>

        @if($payments->isEmpty())
            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-sm">
                You haven't submitted any payment receipts yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[700px]">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider bg-slate-50/50">
                            <th class="py-3 px-4 whitespace-nowrap">Date Paid</th>
                            <th class="py-3 px-4 whitespace-nowrap">Bill Type</th>
                            <th class="py-3 px-4 whitespace-nowrap">Amount</th>
                            <th class="py-3 px-4 whitespace-nowrap">Payment Method</th>
                            <th class="py-3 px-4 whitespace-nowrap">Reference / TxID</th>
                            <th class="py-3 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 text-right whitespace-nowrap">Receipt Slip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $pay)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 text-slate-600 font-medium whitespace-nowrap">{{ $pay->payment_date->format('M d, Y') }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">{{ $pay->bill_type }}</td>
                                <td class="py-3.5 px-4 font-extrabold text-slate-900 text-sm whitespace-nowrap">₦{{ number_format($pay->amount, 2) }}</td>
                                <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">{{ $pay->payment_method }}</td>
                                <td class="py-3.5 px-4">
                                    <code class="text-[11px] font-mono text-slate-700 bg-slate-100 px-2 py-0.5 rounded max-w-[150px] truncate block" title="{{ $pay->transaction_reference }}">
                                        {{ $pay->transaction_reference }}
                                    </code>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($pay->status === 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">
                                            ✓ Verified & Approved
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
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('payments.show', $pay) }}" class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:text-indigo-800 transition">
                                        <span>View Slip</span>
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

            <div class="pt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
