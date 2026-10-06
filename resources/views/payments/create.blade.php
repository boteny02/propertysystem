@extends('layouts.app')

@section('title', 'Submit Payment Receipt - PropNest')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Submit Payment Receipt</h1>
            <p class="text-sm text-slate-500">Provide your transaction reference and upload the payment proof slip.</p>
        </div>
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Payments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Link to specific bill if applicable -->
                @if($selectedBill)
                    <input type="hidden" name="bill_id" value="{{ $selectedBill->id }}">
                    <input type="hidden" name="property_id" value="{{ $selectedBill->property_id }}">
                    <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-bold text-indigo-700 uppercase tracking-wider block">Paying Invoice:</span>
                            <span class="text-sm font-bold text-slate-900">{{ $selectedBill->title }}</span>
                            <span class="text-xs text-slate-500 block">Due: {{ $selectedBill->due_date->format('M d, Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black text-indigo-600">₦{{ number_format($selectedBill->amount, 2) }}</span>
                        </div>
                    </div>
                @else
                    <!-- Bill selection or custom bill -->
                    @if($unpaidBills->isNotEmpty())
                        <div>
                            <label for="bill_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                                Select Existing Unpaid Bill (Optional)
                            </label>
                            <select name="bill_id" id="bill_id" onchange="autoFillBill(this)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                                <option value="">-- Or enter custom payment below --</option>
                                @foreach($unpaidBills as $b)
                                    <option value="{{ $b->id }}" data-type="{{ $b->bill_type }}" data-amount="{{ $b->amount }}" data-property="{{ $b->property_id }}">
                                        {{ $b->title }} — ₦{{ number_format($b->amount, 2) }} (Due: {{ $b->due_date->format('M d') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="property_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Associated Property Unit *
                        </label>
                        <select name="property_id" id="property_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                            @foreach($properties as $prop)
                                <option value="{{ $prop->id }}" {{ old('property_id') == $prop->id ? 'selected' : '' }}>
                                    {{ $prop->name }} ({{ $prop->location }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Bill Type & Amount -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="bill_type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Bill Category *
                        </label>
                        <select name="bill_type" id="bill_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                            @foreach($billTypes as $type)
                                <option value="{{ $type }}" {{ (old('bill_type', $selectedBill->bill_type ?? '') === $type) ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="amount" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Amount Paid (Naira ₦) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-400 font-bold text-sm">₦</span>
                            <input
                                type="number"
                                step="0.01"
                                name="amount"
                                id="amount"
                                value="{{ old('amount', $selectedBill->amount ?? '') }}"
                                required
                                placeholder="0.00"
                                class="w-full pl-8 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none font-bold text-slate-900"
                            >
                        </div>
                    </div>
                </div>

                <!-- Payment Method & Date -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="payment_method" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Payment Method Used *
                        </label>
                        <select name="payment_method" id="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="payment_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Date of Payment *
                        </label>
                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            value="{{ old('payment_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                        >
                    </div>
                </div>

                <!-- Transaction Reference / Hash -->
                <div>
                    <label for="transaction_reference" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Bank Reference Number or Crypto TxID / Hash *
                    </label>
                    <input
                        type="text"
                        name="transaction_reference"
                        id="transaction_reference"
                        value="{{ old('transaction_reference') }}"
                        required
                        placeholder="e.g. WIRE-84920194 or 0x3f9a8b1c..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:border-indigo-500 outline-none"
                    >
                    <p class="text-[11px] text-slate-400 mt-1">This allows the landlord to reconcile your payment against the escrow records.</p>
                </div>

                <!-- Receipt File Upload -->
                <div>
                    <label for="receipt" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Upload Payment Receipt / Proof Slip *
                    </label>
                    <div class="relative p-5 border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl transition bg-slate-50/50 hover:bg-indigo-50/20 group text-center cursor-pointer">
                        <input
                            type="file"
                            name="receipt"
                            id="receipt"
                            required
                            accept="image/*,application/pdf"
                            onchange="previewReceiptFile(this)"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                        >
                        <div id="receipt-upload-prompt" class="space-y-1.5">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto group-hover:scale-110 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div class="text-xs font-bold text-slate-700">
                                <span class="text-indigo-600 underline">Click to browse file</span> or drag & drop receipt here
                            </div>
                            <p class="text-[11px] text-slate-400">PNG, JPG, or PDF (up to 5MB maximum)</p>
                        </div>
                        <div id="receipt-preview-box" class="hidden flex items-center justify-center gap-3 pt-1">
                            <div id="receipt-thumb-container" class="w-12 h-12 rounded-lg bg-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-500 text-xs">
                                <img id="receipt-preview-img" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                                <span id="receipt-file-icon">📄</span>
                            </div>
                            <div class="text-left text-xs">
                                <div id="receipt-file-name" class="font-bold text-slate-800 truncate max-w-[220px]">file.png</div>
                                <div id="receipt-file-size" class="text-[11px] text-slate-400">0 KB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="tenant_notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Additional Notes / Remarks (Optional)
                    </label>
                    <textarea
                        name="tenant_notes"
                        id="tenant_notes"
                        rows="2"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                        placeholder="e.g. Paid for rent plus electricity adjustment..."
                    >{{ old('tenant_notes') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition cursor-pointer"
                >
                    Submit Receipt for Landlord Verification
                </button>
            </form>
        </div>

        <!-- Side Panel: Channels Reference (1 col) -->
        <div class="space-y-4">
            <!-- Bank Box -->
            <div class="p-5 rounded-2xl bg-slate-900 text-white space-y-3 shadow-xs text-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-300 block">
                    Bank Account Details
                </span>
                <div class="space-y-1.5 text-slate-300">
                    <div>Bank: <strong class="text-white">{{ $bankDetails['bank_name'] }}</strong></div>
                    <div>Account: <code class="text-amber-300 font-mono font-bold">{{ $bankDetails['account_number'] }}</code></div>
                    <div>Name: <strong class="text-white">{{ $bankDetails['account_name'] }}</strong></div>
                </div>
            </div>

            <!-- Crypto Box -->
            <div class="p-5 rounded-2xl bg-indigo-950 text-white space-y-3 shadow-xs text-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-300 block">
                    Crypto Addresses
                </span>
                <div class="space-y-2">
                    <div>
                        <span class="text-[10px] text-slate-400 block">USDT (TRC-20):</span>
                        <code class="text-[10px] text-slate-200 font-mono break-all block bg-white/5 p-1 rounded">
                            {{ $cryptoDetails['usdt_trc20']['address'] }}
                        </code>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 block">Bitcoin (BTC):</span>
                        <code class="text-[10px] text-slate-200 font-mono break-all block bg-white/5 p-1 rounded">
                            {{ $cryptoDetails['bitcoin']['address'] }}
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function autoFillBill(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('amount').value = option.dataset.amount || '';
            document.getElementById('bill_type').value = option.dataset.type || '';
            if (option.dataset.property && document.getElementById('property_id')) {
                document.getElementById('property_id').value = option.dataset.property;
            }
        }
    }

    function previewReceiptFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const prompt = document.getElementById('receipt-upload-prompt');
            const previewBox = document.getElementById('receipt-preview-box');
            const nameEl = document.getElementById('receipt-file-name');
            const sizeEl = document.getElementById('receipt-file-size');

            if (prompt) prompt.classList.add('hidden');
            if (previewBox) previewBox.classList.remove('hidden');
            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('receipt-preview-img');
                    const icon = document.getElementById('receipt-file-icon');
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    if (icon) icon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                const img = document.getElementById('receipt-preview-img');
                const icon = document.getElementById('receipt-file-icon');
                if (img) img.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
            }
        }
    }
</script>
@endsection
