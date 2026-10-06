@extends('layouts.app')

@section('title', 'Property Catalogue - Available Rental Properties')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Property Catalogue</h1>
            <p class="text-sm text-slate-500 mt-1">
                Browse our selection of quality verified apartments, penthouses, villas, and townhouses.
            </p>
        </div>

        @auth
            @if(auth()->user()->isLandlord())
                <div>
                    <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-600/20 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Property
                    </a>
                </div>
            @endif
        @endauth
    </div>

    <!-- Filter and Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs">
        <form action="{{ route('properties.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Keyword search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Search Keywords</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search by name, address, city..."
                            class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Property Type -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Property Type</label>
                    <select name="type" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        <option value="all">All Types</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bedrooms -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bedrooms</label>
                    <select name="bedrooms" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        <option value="all">Any Bedrooms</option>
                        <option value="1" {{ request('bedrooms') === '1' ? 'selected' : '' }}>1 Bedroom</option>
                        <option value="2" {{ request('bedrooms') === '2' ? 'selected' : '' }}>2 Bedrooms</option>
                        <option value="3" {{ request('bedrooms') === '3' ? 'selected' : '' }}>3 Bedrooms</option>
                        <option value="4+" {{ request('bedrooms') === '4+' ? 'selected' : '' }}>4+ Bedrooms</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Availability</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        <option value="all">All Statuses</option>
                        <option value="available" {{ request('status', 'available') === 'available' ? 'selected' : '' }}>Available for Rent</option>
                        <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Rented / Occupied</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
            </div>

            <!-- Second row: Price range & Sort & Submit -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-600">Price Range:</span>
                    <input
                        type="number"
                        name="min_price"
                        value="{{ request('min_price') }}"
                        placeholder="Min ₦"
                        class="w-28 px-3 py-1.5 rounded-xl border border-slate-300 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white"
                    >
                    <span class="text-slate-400 font-bold">-</span>
                    <input
                        type="number"
                        name="max_price"
                        value="{{ request('max_price') }}"
                        placeholder="Max ₦"
                        class="w-28 px-3 py-1.5 rounded-xl border border-slate-300 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-600">Sort By:</span>
                    <select name="sort" class="px-3 py-1.5 rounded-xl border border-slate-300 outline-none bg-white focus:border-indigo-500">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Listed</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>

                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition shadow-xs cursor-pointer">
                        Apply Filters
                    </button>

                    @if(request()->anyFilled(['q', 'type', 'bedrooms', 'status', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('properties.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Properties Grid -->
    @if($properties->isEmpty())
        <div class="p-12 text-center bg-white rounded-3xl border border-slate-200 shadow-xs space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">No properties match your filter criteria</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Try widening your price range, clearing some filters, or checking back soon as new properties are added regularly.
            </p>
            <div>
                <a href="{{ route('properties.index') }}" class="inline-block px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                    Reset All Filters
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between hover:shadow-lg hover:-translate-y-0.5 transition duration-200 group">
                    <div>
                        <!-- Property Image Thumbnail -->
                        <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                            <img
                                src="{{ $property->display_image }}"
                                alt="{{ $property->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                loading="lazy"
                            >

                            <!-- Property Type Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-900/80 backdrop-blur-md text-white shadow-xs">
                                    {{ $property->property_type }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if($property->status === 'available')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500 text-white shadow-md">
                                        ✓ Available
                                    </span>
                                @elseif($property->status === 'rented')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-600 text-white shadow-md">
                                        Occupied
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500 text-white shadow-md">
                                        Maintenance
                                    </span>
                                @endif
                            </div>

                            <!-- Price Tag Overlay -->
                            <div class="absolute bottom-3 left-3">
                                <div class="px-3 py-1 rounded-xl bg-white/95 backdrop-blur-md text-slate-900 font-extrabold text-sm shadow-md">
                                    {{ $property->formatted_price }}
                                </div>
                            </div>
                        </div>

                        <!-- Card Content Details -->
                        <div class="p-5 space-y-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1 group-hover:text-indigo-600 transition">
                                    <a href="{{ route('properties.show', $property) }}">{{ $property->name }}</a>
                                </h3>
                                <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-1">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $property->location }}, {{ $property->city }}</span>
                                </div>
                            </div>

                            <!-- Specs row -->
                            <div class="flex items-center gap-4 py-2 border-y border-slate-100 text-xs text-slate-600">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="font-bold text-slate-800">{{ $property->bedrooms }}</span> Beds
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-bold text-slate-800">{{ $property->bathrooms }}</span> Baths
                                </div>
                                <div class="text-[11px] text-slate-400 capitalize">
                                    {{ $property->rent_frequency }} lease
                                </div>
                            </div>

                            <!-- Amenities Pills (preview first 3) -->
                            @if($property->facilities)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($property->facilities, 0, 3) as $facility)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                            {{ $facility }}
                                        </span>
                                    @endforeach
                                    @if(count($property->facilities) > 3)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold text-slate-400">
                                            +{{ count($property->facilities) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Actions Footer -->
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('properties.show', $property) }}" class="flex-1 text-center py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs transition">
                            View Full Details
                        </a>

                        @auth
                            @if(auth()->user()->isLandlord())
                                <a href="{{ route('admin.properties.edit', $property) }}" class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-white hover:text-indigo-600 transition" title="Edit Property">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $properties->links() }}
        </div>
    @endif
</div>
@endsection
