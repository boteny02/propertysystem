@extends('layouts.app')

@section('title', 'Sign In - PropNest')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <div class="text-center mb-8">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to your account with your assigned role</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

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
                    autofocus
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                    placeholder="you@example.com"
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Password
                    </label>
                </div>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm transition outline-none"
                    placeholder="••••••••"
                >
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    <span>Remember me</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-500/20 hover:shadow-lg transition cursor-pointer"
            >
                Sign In to Account
            </button>
        </form>

        <!-- Instant Demo Logins -->
        <div class="mt-8 pt-6 border-t border-slate-100">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-center mb-3">
                Quick Demo 1-Click Sign In
            </div>
            <div class="grid grid-cols-2 gap-2.5">
                <a
                    href="{{ route('demo.login', 'landlord') }}"
                    class="flex flex-col items-center justify-center p-3 rounded-xl border border-amber-200 bg-amber-50/50 hover:bg-amber-100/50 text-amber-900 transition text-center group"
                >
                    <span class="text-xs font-bold flex items-center gap-1">
                        👑 Landlord / Admin
                    </span>
                    <span class="text-[11px] text-amber-700 mt-0.5">Alexander Vance</span>
                </a>

                <a
                    href="{{ route('demo.login', 'tenant') }}"
                    class="flex flex-col items-center justify-center p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100/50 text-emerald-900 transition text-center group"
                >
                    <span class="text-xs font-bold flex items-center gap-1">
                        👤 Tenant
                    </span>
                    <span class="text-[11px] text-emerald-700 mt-0.5">Michael Chen</span>
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            Don't have an account yet?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 underline underline-offset-2">
                Register as a Tenant
            </a>
        </p>
    </div>
</div>
@endsection
