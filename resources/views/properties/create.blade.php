@extends('layouts.app')

@section('title', 'Add New Property - PropNest')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Add New Property to Catalogue</h1>
            <p class="text-sm text-slate-500">Create a rental unit listing with full specifications and amenities.</p>
        </div>
        <a href="{{ route('properties.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Cancel & Return
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Property Name & Location -->
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Property Name / Unit Title *
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm outline-none"
                        placeholder="e.g. Sunset Heights Apartment 4B"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label for="location" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Street Address *
                        </label>
                        <input
                            type="text"
                            name="location"
                            id="location"
                            value="{{ old('location') }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm outline-none"
                            placeholder="e.g. 104 Oakwood Heights Road"
                        >
                    </div>

                    <div>
                        <label for="city" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            City / District *
                        </label>
                        <input
                            type="text"
                            name="city"
                            id="city"
                            value="{{ old('city') }}"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm outline-none"
                            placeholder="e.g. Metropolis"
                        >
                    </div>
                </div>
            </div>

            <!-- Property Type, Beds, Baths, Status -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-100">
                <div>
                    <label for="property_type" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Type *
                    </label>
                    <select name="property_type" id="property_type" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type }}" {{ old('property_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="bedrooms" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Bedrooms *
                    </label>
                    <input
                        type="number"
                        name="bedrooms"
                        id="bedrooms"
                        value="{{ old('bedrooms', 1) }}"
                        min="0"
                        required
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    >
                </div>

                <div>
                    <label for="bathrooms" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Bathrooms *
                    </label>
                    <input
                        type="number"
                        name="bathrooms"
                        id="bathrooms"
                        value="{{ old('bathrooms', 1) }}"
                        min="0"
                        required
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    >
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Initial Status *
                    </label>
                    <select name="status" id="status" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="rented" {{ old('status') === 'rented' ? 'selected' : '' }}>Rented</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            <!-- Price and Billing Frequency -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                <div>
                    <label for="rental_price" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Rental Price (Naira ₦) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-slate-400 font-bold text-sm">₦</span>
                        <input
                            type="number"
                            step="0.01"
                            name="rental_price"
                            id="rental_price"
                            value="{{ old('rental_price') }}"
                            required
                            placeholder="1500.00"
                            class="w-full pl-8 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                        >
                    </div>
                </div>

                <div>
                    <label for="rent_frequency" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Rent Payment Frequency *
                    </label>
                    <select name="rent_frequency" id="rent_frequency" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        <option value="monthly" {{ old('rent_frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('rent_frequency') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="annually" {{ old('rent_frequency') === 'annually' ? 'selected' : '' }}>Annually</option>
                    </select>
                </div>
            </div>

            <!-- Initial Tenant Assignment (Optional) -->
            <div class="pt-4 border-t border-slate-100">
                <label for="current_tenant_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Assign Tenant (Optional)
                </label>
                <select name="current_tenant_id" id="current_tenant_id" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                    <option value="">-- None (Keep Vacant) --</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('current_tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }} ({{ $tenant->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Facilities & Amenities Checkboxes -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Available Facilities & Amenities
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($availableFacilities as $facility)
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs font-medium text-slate-700">
                            <input
                                type="checkbox"
                                name="facilities[]"
                                value="{{ $facility }}"
                                {{ is_array(old('facilities')) && in_array($facility, old('facilities')) ? 'checked' : '' }}
                                class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300"
                            >
                            <span>{{ $facility }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Description -->
            <div class="pt-4 border-t border-slate-100">
                <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Property Description & Remarks
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    placeholder="Provide details about neighborhood, furnishings, view, or specific policies..."
                >{{ old('description') }}</textarea>
            </div>

            <!-- Featured Image Upload -->
            <div class="pt-4 border-t border-slate-100">
                <label for="image" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Featured Property Image
                </label>
                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/*"
                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                >
                <p class="text-[11px] text-slate-400 mt-1">Accepts JPEG, PNG, WEBP up to 4MB.</p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100">
                <button
                    type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
                >
                    Save & Publish Property to Catalogue
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
