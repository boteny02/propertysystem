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
                <div class="relative p-5 border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-2xl transition bg-slate-50/50 hover:bg-indigo-50/20 group text-center cursor-pointer">
                    <input
                        type="file"
                        name="photo"
                        id="photo"
                        accept="image/*"
                        onchange="previewDefectPhoto(this)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    >
                    <div id="photo-upload-prompt" class="space-y-1.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto group-hover:scale-110 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-xs font-bold text-slate-700">
                            <span class="text-indigo-600 underline">Click to take or select photo</span> or drag image here
                        </div>
                        <p class="text-[11px] text-slate-400">Clear photos help technicians bring the right replacement tools and parts.</p>
                    </div>
                    <div id="photo-preview-box" class="hidden flex items-center justify-center gap-3 pt-1">
                        <div class="w-14 h-14 rounded-lg bg-slate-200 overflow-hidden shrink-0 border border-slate-300">
                            <img id="photo-preview-img" src="#" alt="Defect preview" class="w-full h-full object-cover">
                        </div>
                        <div class="text-left text-xs">
                            <div id="photo-file-name" class="font-bold text-slate-800 truncate max-w-[220px]">photo.jpg</div>
                            <div id="photo-file-size" class="text-[11px] text-slate-400">0 KB</div>
                        </div>
                    </div>
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

<script>
    function previewDefectPhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const prompt = document.getElementById('photo-upload-prompt');
            const previewBox = document.getElementById('photo-preview-box');
            const nameEl = document.getElementById('photo-file-name');
            const sizeEl = document.getElementById('photo-file-size');
            const img = document.getElementById('photo-preview-img');

            if (prompt) prompt.classList.add('hidden');
            if (previewBox) previewBox.classList.remove('hidden');
            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';

            const reader = new FileReader();
            reader.onload = function(e) {
                if (img) img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
