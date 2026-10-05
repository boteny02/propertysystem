@extends('layouts.app')

@section('title', 'Issue New Bill to Tenant - PropNest')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Issue New Bill / Invoice</h1>
            <p class="text-sm text-slate-500">Generate an electronic bill for rent, electricity, or maintenance charges.</p>
        </div>
        <a href="{{ route('payments.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Payments
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.bills.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Property Selection -->
            <div>
                <label for="property_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Property Unit *
                </label>
                <select name="property_id" id="property_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                    @foreach($properties as $prop)
                        <option value="{{ $prop->id }}" {{ old('property_id') == $prop->id ? 'selected' : '' }}>
                            {{ $prop->name }} ({{ $prop->location }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tenant Selection -->
            <div>
                <label for="tenant_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Bill Recipient (Tenant) *
                </label>
                <select name="tenant_id" id="tenant_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                    @foreach($tenants as $ten)
                        <option value="{{ $ten->id }}" {{ old('tenant_id') == $ten->id ? 'selected' : '' }}>
                            {{ $ten->name }} ({{ $ten->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bill Type & Title -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="bill_type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Bill Category *
                    </label>
                    <select name="bill_type" id="bill_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        @foreach($billTypes as $type)
                            <option value="{{ $type }}" {{ old('bill_type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Bill / Invoice Title *
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="e.g. November 2026 House Rent"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    >
                </div>
            </div>

            <!-- Amount and Due Date -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="amount" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Amount Due (Naira ₦) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-slate-400 font-bold text-sm">₦</span>
                        <input
                            type="number"
                            step="0.01"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            required
                            placeholder="0.00"
                            class="w-full pl-8 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none font-bold"
                        >
                    </div>
                </div>

                <div>
                    <label for="due_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Payment Due Date *
                    </label>
                    <input
                        type="date"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    >
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Invoice Notes & Breakdown (Optional)
                </label>
                <textarea
                    name="notes"
                    id="notes"
                    rows="3"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    placeholder="e.g. Meter reading: 45290 kWh to 45680 kWh at $0.15/kWh..."
                >{{ old('notes') }}</textarea>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
            >
                Issue Bill & Notify Tenant
            </button>
        </form>
    </div>
</div>
@endsection
