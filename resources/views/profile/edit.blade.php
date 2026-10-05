@extends('layouts.app')

@section('title', 'Profile Settings - PropNest')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Account & Profile Settings</h1>
            <p class="text-sm text-slate-500">Manage your contact details, residential address, and credentials.</p>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $user->isLandlord() ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
            Role: {{ $user->role }}
        </span>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Full Name *
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Email Address *
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Phone Number
                </label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    value="{{ old('phone', $user->phone) }}"
                    placeholder="+1 (555) 000-0000"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Physical / Residential Address
                </label>
                <input
                    type="text"
                    name="address"
                    id="address"
                    value="{{ old('address', $user->address) }}"
                    placeholder="e.g. Unit 402, Skyline Towers"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <!-- Password Change Section -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Change Password (Optional)</h3>

                <div>
                    <label for="current_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Current Password
                    </label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="new_password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            New Password
                        </label>
                        <input
                            type="password"
                            name="new_password"
                            id="new_password"
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                        >
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Confirm New Password
                        </label>
                        <input
                            type="password"
                            name="new_password_confirmation"
                            id="new_password_confirmation"
                            placeholder="••••••••"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                        >
                    </div>
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
            >
                Save Profile Changes
            </button>
        </form>
    </div>
</div>
@endsection
