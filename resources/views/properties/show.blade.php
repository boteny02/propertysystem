@extends('layouts.app')

@section('title', $property->name . ' - PropNest')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumb & Top Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2 text-slate-500">
            <a href="{{ route('properties.index') }}" class="hover:text-indigo-600 transition">Catalogue</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate">{{ $property->name }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('properties.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                &larr; Back to Catalogue
            </a>

            @auth
                @if(auth()->user()->isLandlord())
                    <a href="{{ route('admin.properties.edit', $property) }}" class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition">
                        Edit Specs
                    </a>

                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this property?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold border border-rose-200 transition">
                            Delete
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <!-- Main Property Overview Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Hero Image -->
        <div class="relative h-80 sm:h-96 w-full bg-slate-900 overflow-hidden">
            <img src="{{ $property->display_image }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30"></div>

            <!-- Top Badges -->
            <div class="absolute top-4 left-4 flex items-center gap-2">
                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-slate-900/80 backdrop-blur-md text-white shadow-md">
                    {{ $property->property_type }}
                </span>
                @if($property->status === 'available')
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-500 text-white shadow-md">
                        ✓ Available for Rent
                    </span>
                @elseif($property->status === 'rented')
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-indigo-600 text-white shadow-md">
                        Occupied / Leased
                    </span>
                @else
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-500 text-white shadow-md">
                        Under Maintenance
                    </span>
                @endif
            </div>

            <!-- Bottom Title and Price inside Hero -->
            <div class="absolute bottom-4 left-4 right-4 flex flex-col sm:flex-row sm:items-end justify-between gap-4 text-white">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight drop-shadow-md">
                        {{ $property->name }}
                    </h1>
                    <p class="text-sm text-slate-200 flex items-center gap-1.5 mt-1 drop-shadow-sm">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $property->location }}, {{ $property->city }}
                    </p>
                </div>

                <div class="bg-white/95 backdrop-blur-md text-slate-900 px-5 py-3 rounded-2xl shadow-xl shrink-0 text-right">
                    <span class="text-xs text-slate-500 font-semibold block uppercase tracking-wider">Rental Price</span>
                    <span class="text-2xl font-black text-indigo-600 leading-tight">
                        {{ $property->formatted_price }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Specifications Strip -->
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 border-b border-slate-100 bg-slate-50/50 p-4 text-center">
            <div class="p-3">
                <span class="text-xs text-slate-400 block font-medium">Bedrooms</span>
                <span class="text-base font-bold text-slate-800">{{ $property->bedrooms }} Bedrooms</span>
            </div>
            <div class="p-3">
                <span class="text-xs text-slate-400 block font-medium">Bathrooms</span>
                <span class="text-base font-bold text-slate-800">{{ $property->bathrooms }} Bathrooms</span>
            </div>
            <div class="p-3">
                <span class="text-xs text-slate-400 block font-medium">Property Type</span>
                <span class="text-base font-bold text-slate-800">{{ $property->property_type }}</span>
            </div>
            <div class="p-3">
                <span class="text-xs text-slate-400 block font-medium">Rent Frequency</span>
                <span class="text-base font-bold text-slate-800 capitalize">{{ $property->rent_frequency }}</span>
            </div>
        </div>

        <!-- Body Details: Description, Facilities, Landlord Info -->
        <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Description & Amenities -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-2">About This Property</h2>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $property->description ?: 'No detailed description provided for this property listing.' }}
                    </p>
                </div>

                <!-- Facilities & Amenities -->
                <div>
                    <h2 class="text-lg font-bold text-slate-900 mb-3">Available Facilities & Amenities</h2>
                    @if($property->facilities && count($property->facilities) > 0)
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($property->facilities as $fac)
                                <div class="flex items-center gap-2 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs font-semibold text-slate-700">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ $fac }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400">No specific facilities specified for this unit.</p>
                    @endif
                </div>
            </div>

            <!-- Right Col: Landlord / Tenant Management Card -->
            <div class="space-y-6">
                <!-- Landlord Contact Profile Card -->
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        Property Management Contact
                    </h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                            {{ strtoupper(substr($property->landlord->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $property->landlord->name }}</div>
                            <div class="text-xs text-slate-500">Property Administrator</div>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs text-slate-600 pt-2 border-t border-slate-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $property->landlord->email }}</span>
                        </div>
                        @if($property->landlord->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ $property->landlord->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Contextual Tenant Action -->
                    @auth
                        @if(auth()->user()->isTenant())
                            @if($property->current_tenant_id === auth()->id())
                                <div class="pt-2 border-t border-slate-200 space-y-2">
                                    <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-semibold text-center">
                                        ✓ You are currently renting this unit
                                    </div>
                                    <a href="{{ route('payments.create', ['property_id' => $property->id]) }}" class="block w-full py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center transition">
                                        Pay Rent / Settle Bill
                                    </a>
                                    <a href="{{ route('maintenance.create', ['property_id' => $property->id]) }}" class="block w-full py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs text-center transition">
                                        Submit Maintenance Request
                                    </a>
                                </div>
                            @elseif($property->isAvailable())
                                <div class="pt-2 border-t border-slate-200">
                                    <a href="mailto:{{ $property->landlord->email }}?subject=Inquiry for {{ urlencode($property->name) }}" class="block w-full py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs text-center shadow-xs transition">
                                        Contact Landlord to Rent
                                    </a>
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="pt-2 border-t border-slate-200">
                            <a href="{{ route('login') }}" class="block w-full py-2.5 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs text-center shadow-xs transition">
                                Sign In to Rent / Inquire
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Landlord Tenancy Assignment Widget -->
                @auth
                    @if(auth()->user()->isLandlord())
                        <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 p-5 space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-900">
                                Assign / Manage Tenancy
                            </h3>
                            <p class="text-xs text-slate-600">
                                Current Status: <strong class="capitalize font-bold">{{ $property->status }}</strong><br>
                                Current Tenant: <strong class="font-bold">{{ $property->currentTenant ? $property->currentTenant->name : 'None (Vacant)' }}</strong>
                            </p>

                            <form action="{{ route('admin.properties.assignTenant', $property) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Select Tenant</label>
                                    <select name="tenant_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-white focus:border-indigo-500 outline-none">
                                        <option value="">-- Set as Vacant (No Tenant) --</option>
                                        @foreach(\App\Models\User::where('role', 'tenant')->get() as $ten)
                                            <option value="{{ $ten->id }}" {{ $property->current_tenant_id === $ten->id ? 'selected' : '' }}>
                                                {{ $ten->name }} ({{ $ten->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition cursor-pointer">
                                    Update Tenancy Assignment
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Related Properties -->
    @if($relatedProperties->isNotEmpty())
        <div class="space-y-4">
            <h2 class="text-xl font-bold text-slate-900">Similar Properties in Catalogue</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedProperties as $rel)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition">
                        <div class="h-40 bg-slate-100">
                            <img src="{{ $rel->display_image }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h4 class="text-sm font-bold text-slate-900 truncate">{{ $rel->name }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ $rel->location }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-600">{{ $rel->formatted_price }}</span>
                                <a href="{{ route('properties.show', $rel) }}" class="text-xs font-semibold text-slate-700 hover:text-indigo-600">
                                    View &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
