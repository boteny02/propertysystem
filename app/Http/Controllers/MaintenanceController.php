<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isLandlord()) {
            $query = MaintenanceRequest::with(['property', 'tenant']);

            if ($request->filled('status') && $request->input('status') !== 'all') {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('urgency') && $request->input('urgency') !== 'all') {
                $query->where('urgency', $request->input('urgency'));
            }

            if ($request->filled('category') && $request->input('category') !== 'all') {
                $query->where('category', $request->input('category'));
            }

            $requests = $query->latest()->paginate(10)->withQueryString();

            $pendingCount = MaintenanceRequest::where('status', 'pending')->count();
            $inProgressCount = MaintenanceRequest::where('status', 'in_progress')->count();
            $resolvedCount = MaintenanceRequest::where('status', 'resolved')->count();
            $emergencyCount = MaintenanceRequest::where('urgency', 'emergency')->where('status', '!=', 'resolved')->count();

            return view('maintenance.landlord_index', compact(
                'requests',
                'pendingCount',
                'inProgressCount',
                'resolvedCount',
                'emergencyCount'
            ));
        }

        // Tenant view
        $requests = MaintenanceRequest::where('tenant_id', $user->id)
            ->with('property')
            ->latest()
            ->paginate(10);

        return view('maintenance.tenant_index', compact('requests'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $properties = Property::where('current_tenant_id', $user->id)->get();

        if ($properties->isEmpty()) {
            $properties = Property::all();
        }

        $categories = [
            'Plumbing',
            'Electrical',
            'Appliance',
            'HVAC / Air Conditioning',
            'Carpentry / Structural',
            'Pest Control',
            'Painting',
            'Water Supply',
            'Other',
        ];

        $urgencies = [
            'low' => 'Low (Minor inconvenience)',
            'medium' => 'Medium (Needs attention within a few days)',
            'high' => 'High (Impacting daily living)',
            'emergency' => 'Emergency (Immediate safety hazard or major leak)',
        ];

        return view('maintenance.create', compact('properties', 'categories', 'urgencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'urgency' => ['required', 'in:low,medium,high,emergency'],
            'description' => ['required', 'string', 'min:10'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('maintenance', 'public');
        }

        $maintenance = MaintenanceRequest::create([
            'property_id' => $validated['property_id'],
            'tenant_id' => $user->id,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'urgency' => $validated['urgency'],
            'description' => $validated['description'],
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        // Notify landlord(s)
        $landlords = User::where('role', 'landlord')->get();
        foreach ($landlords as $landlord) {
            SystemNotification::create([
                'user_id' => $landlord->id,
                'sender_id' => $user->id,
                'title' => 'New Maintenance Request: '.$validated['title'],
                'message' => "Tenant {$user->name} reported a {$validated['urgency']} urgency {$validated['category']} issue.",
                'type' => 'maintenance',
                'link' => route('maintenance.show', $maintenance),
            ]);
        }

        return redirect()->route('maintenance.show', $maintenance)
            ->with('success', 'Maintenance request submitted electronically! The landlord has been notified.');
    }

    public function show(MaintenanceRequest $maintenance): View
    {
        $user = auth()->user();

        // Ensure tenant can only view their own request
        if ($user->isTenant() && $maintenance->tenant_id !== $user->id) {
            abort(403);
        }

        $maintenance->load(['property', 'tenant']);

        return view('maintenance.show', compact('maintenance'));
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenance): RedirectResponse
    {
        if (! $request->user()->isLandlord()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,resolved,cancelled'],
            'scheduled_date' => ['nullable', 'date'],
            'technician_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $resolvedAt = ($validated['status'] === 'resolved') ? now() : $maintenance->resolved_at;

        $maintenance->update([
            'status' => $validated['status'],
            'scheduled_date' => $validated['scheduled_date'] ?? $maintenance->scheduled_date,
            'technician_notes' => $validated['technician_notes'] ?? $maintenance->technician_notes,
            'resolved_at' => $resolvedAt,
        ]);

        // Notify tenant
        $statusLabels = [
            'pending' => 'Pending Review',
            'in_progress' => 'In Progress (Technician Assigned)',
            'resolved' => 'Resolved & Completed',
            'cancelled' => 'Cancelled',
        ];

        SystemNotification::create([
            'user_id' => $maintenance->tenant_id,
            'sender_id' => $request->user()->id,
            'title' => 'Maintenance Request Updated: '.$maintenance->title,
            'message' => "Your maintenance request status has been updated to: '{$statusLabels[$validated['status']]}'.".
                ($validated['technician_notes'] ? " Note: {$validated['technician_notes']}" : ''),
            'type' => 'maintenance',
            'link' => route('maintenance.show', $maintenance),
        ]);

        return back()->with('success', 'Maintenance status and notes successfully updated!');
    }
}
