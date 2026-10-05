<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isLandlord()) {
            return $this->landlordDashboard($user);
        }

        return $this->tenantDashboard($user);
    }

    private function landlordDashboard($user): View
    {
        $totalProperties = Property::count();
        $occupiedProperties = Property::whereNotNull('current_tenant_id')->orWhere('status', 'rented')->count();
        $availableProperties = Property::where('status', 'available')->count();
        $maintenanceProperties = Property::where('status', 'maintenance')->count();

        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $pendingPaymentsCount = Payment::where('status', 'pending')->count();
        $pendingPaymentsAmount = Payment::where('status', 'pending')->sum('amount');

        $activeMaintenanceCount = MaintenanceRequest::whereIn('status', ['pending', 'in_progress'])->count();

        $recentPayments = Payment::with(['tenant', 'property'])->latest()->take(5)->get();
        $recentMaintenance = MaintenanceRequest::with(['tenant', 'property'])->latest()->take(5)->get();
        $recentProperties = Property::with('currentTenant')->latest()->take(4)->get();

        return view('dashboard.landlord', compact(
            'totalProperties',
            'occupiedProperties',
            'availableProperties',
            'maintenanceProperties',
            'totalRevenue',
            'pendingPaymentsCount',
            'pendingPaymentsAmount',
            'activeMaintenanceCount',
            'recentPayments',
            'recentMaintenance',
            'recentProperties'
        ));
    }

    private function tenantDashboard($user): View
    {
        $rentedProperty = Property::where('current_tenant_id', $user->id)->first();

        $unpaidBills = Bill::where('tenant_id', $user->id)
            ->where('status', 'unpaid')
            ->orderBy('due_date')
            ->get();
        $totalDue = $unpaidBills->sum('amount');

        $recentPayments = Payment::where('tenant_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $activeMaintenance = MaintenanceRequest::where('tenant_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->get();

        $recentAnnouncements = SystemNotification::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('type', 'announcement');
        })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.tenant', compact(
            'rentedProperty',
            'unpaidBills',
            'totalDue',
            'recentPayments',
            'activeMaintenance',
            'recentAnnouncements'
        ));
    }
}
