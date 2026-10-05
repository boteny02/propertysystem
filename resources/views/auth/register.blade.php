@extends('layouts.app')

@section('title', 'Register - PropNest')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create an Account</h1>
            <p class="text-sm text-slate-500 mt-1">Join PropNest to manage or rent properties</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Full Name
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                    placeholder="e.g. John Doe"
                >
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email Address
                </label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                    placeholder="john@example.com"
                >
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Phone Number
                </label>
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    value="{{ old('phone') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                    placeholder="+1 (555) 000-0000"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Select Your Role
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 has-checked:border-indigo-600 has-checked:bg-indigo-50/40 transition">
                        <input type="radio" name="role" value="tenant" {{ old('role', 'tenant') === 'tenant' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Tenant</div>
                            <div class="text-[10px] text-slate-500">Rent & Pay Bills</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-indigo-300 has-checked:border-indigo-600 has-checked:bg-indigo-50/40 transition">
                        <input type="radio" name="role" value="landlord" {{ old('role') === 'landlord' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Landlord / Admin</div>
                            <div class="text-[10px] text-slate-500">Manage Properties</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                        placeholder="••••••••"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Confirm
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                        placeholder="••••••••"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-500/20 hover:shadow-lg transition cursor-pointer"
            >
                Create Account
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline underline-offset-2">
                Sign In
            </a>
        </p>
    </div>
</div>
@endsection
