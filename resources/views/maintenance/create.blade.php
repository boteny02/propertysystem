@extends('layouts.app')

@section('title', 'Submit Maintenance Request - PropNest')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Report a Maintenance Problem</h1>
            <p class="text-sm text-slate-500">Provide details about repairs or issues requiring landlord attention.</p>
        </div>
        <a href="{{ route('maintenance.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Cancel & Return
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Property Unit -->
            <div>
                <label for="property_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Property Unit *
                </label>
                <select name="property_id" id="property_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                    @foreach($properties as $prop)
                        <option value="{{ $prop->id }}" {{ (request('property_id') == $prop->id || old('property_id') == $prop->id) ? 'selected' : '' }}>
                            {{ $prop->name }} ({{ $prop->location }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category and Urgency -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Issue Category *
                    </label>
                    <select name="category" id="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="urgency" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                        Urgency Level *
                    </label>
                    <select name="urgency" id="urgency" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm bg-white focus:border-indigo-500 outline-none">
                        @foreach($urgencies as $key => $label)
                            <option value="{{ $key }}" {{ old('urgency', 'medium') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Summary / Problem Title *
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    required
                    placeholder="e.g. Master Bathroom Water Heater Pressure Drop"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                >
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Detailed Description of the Defect *
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 outline-none"
                    placeholder="Please explain the symptoms, when it started, and any immediate actions taken (e.g. turned off main valve)..."
                >{{ old('description') }}</textarea>
            </div>

            <!-- Photo Upload -->
            <div>
                <label for="photo" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Attach Photo of Problem (Optional)
                </label>
                <div class="p-4 border-2 border-dashed border-slate-300 rounded-2xl hover:border-indigo-400 transition bg-slate-50/50">
                    <input
                        type="file"
                        name="photo"
                        id="photo"
                        accept="image/*"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer"
                    >
                    <p class="text-[11px] text-slate-400 mt-1">Clear photos help our technicians bring the right replacement tools and parts.</p>
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/20 transition cursor-pointer"
            >
                Submit Electronic Maintenance Request
            </button>
        </form>
    </div>
</div>
@endsection
