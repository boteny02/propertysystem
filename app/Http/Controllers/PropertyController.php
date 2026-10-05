<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Property::with(['landlord', 'currentTenant']);

        // Search
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Property Type
        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('property_type', $request->input('type'));
        }

        // Bedrooms
        if ($request->filled('bedrooms') && $request->input('bedrooms') !== 'all') {
            if ($request->input('bedrooms') === '4+') {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', (int) $request->input('bedrooms'));
            }
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('rental_price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('rental_price', '<=', (float) $request->input('max_price'));
        }

        // Status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('rental_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('rental_price', 'desc');
        } else {
            $query->latest();
        }

        $properties = $query->paginate(9)->withQueryString();

        $propertyTypes = ['Apartment', 'Studio', 'Duplex', 'Villa', 'Townhouse', 'Penthouse', 'Single Room'];

        return view('properties.index', compact('properties', 'propertyTypes'));
    }

    public function show(Property $property): View
    {
        $property->load(['landlord', 'currentTenant']);
        $relatedProperties = Property::where('id', '!=', $property->id)
            ->where('property_type', $property->property_type)
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'relatedProperties'));
    }

    public function create(): View
    {
        $tenants = User::where('role', 'tenant')->get();
        $propertyTypes = ['Apartment', 'Studio', 'Duplex', 'Villa', 'Townhouse', 'Penthouse', 'Single Room'];
        $availableFacilities = [
            '24/7 Electricity', 'WiFi Internet', 'Dedicated Parking', 'Security / CCTV',
            'Swimming Pool', 'Fitness Gym', 'Air Conditioning', 'Borehole Water',
            'Furnished', 'Balcony / Patio', 'Waste Disposal', 'Elevator',
        ];

        return view('properties.create', compact('tenants', 'propertyTypes', 'availableFacilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'property_type' => ['required', 'string'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'rental_price' => ['required', 'numeric', 'min:0'],
            'rent_frequency' => ['required', 'in:monthly,quarterly,annually'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'current_tenant_id' => ['nullable', 'exists:users,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('image')) {
            $featuredImagePath = $request->file('image')->store('properties', 'public');
        }

        $property = Property::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(5),
            'location' => $validated['location'],
            'city' => $validated['city'],
            'property_type' => $validated['property_type'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'rental_price' => $validated['rental_price'],
            'rent_frequency' => $validated['rent_frequency'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
            'facilities' => $validated['facilities'] ?? [],
            'featured_image' => $featuredImagePath,
            'current_tenant_id' => $validated['current_tenant_id'] ?? null,
        ]);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property successfully added to catalogue!');
    }

    public function edit(Property $property): View
    {
        $tenants = User::where('role', 'tenant')->get();
        $propertyTypes = ['Apartment', 'Studio', 'Duplex', 'Villa', 'Townhouse', 'Penthouse', 'Single Room'];
        $availableFacilities = [
            '24/7 Electricity', 'WiFi Internet', 'Dedicated Parking', 'Security / CCTV',
            'Swimming Pool', 'Fitness Gym', 'Air Conditioning', 'Borehole Water',
            'Furnished', 'Balcony / Patio', 'Waste Disposal', 'Elevator',
        ];

        return view('properties.edit', compact('property', 'tenants', 'propertyTypes', 'availableFacilities'));
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'property_type' => ['required', 'string'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'rental_price' => ['required', 'numeric', 'min:0'],
            'rent_frequency' => ['required', 'in:monthly,quarterly,annually'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'current_tenant_id' => ['nullable', 'exists:users,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            if ($property->featured_image && ! str_starts_with($property->featured_image, 'http')) {
                Storage::disk('public')->delete($property->featured_image);
            }
            $property->featured_image = $request->file('image')->store('properties', 'public');
        }

        $property->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'city' => $validated['city'],
            'property_type' => $validated['property_type'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'rental_price' => $validated['rental_price'],
            'rent_frequency' => $validated['rent_frequency'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
            'facilities' => $validated['facilities'] ?? [],
            'current_tenant_id' => $validated['current_tenant_id'] ?? null,
        ]);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property): RedirectResponse
    {
        if ($property->featured_image && ! str_starts_with($property->featured_image, 'http')) {
            Storage::disk('public')->delete($property->featured_image);
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property removed from catalogue.');
    }

    public function assignTenant(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['nullable', 'exists:users,id'],
        ]);

        $tenantId = $validated['tenant_id'];

        $property->update([
            'current_tenant_id' => $tenantId,
            'status' => $tenantId ? 'rented' : 'available',
        ]);

        $msg = $tenantId ? 'Tenant assigned and status set to Rented.' : 'Tenant removed and property marked as Available.';

        return back()->with('success', $msg);
    }
}
