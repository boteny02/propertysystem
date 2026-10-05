<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Property;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isLandlord()) {
            $query = Payment::with(['tenant', 'property', 'bill', 'verifier']);

            if ($request->filled('status') && $request->input('status') !== 'all') {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('bill_type') && $request->input('bill_type') !== 'all') {
                $query->where('bill_type', $request->input('bill_type'));
            }

            $payments = $query->latest()->paginate(10)->withQueryString();

            $pendingCount = Payment::where('status', 'pending')->count();
            $verifiedCount = Payment::where('status', 'verified')->count();
            $rejectedCount = Payment::where('status', 'rejected')->count();
            $totalCollected = Payment::where('status', 'verified')->sum('amount');

            $allBills = Bill::with(['property', 'tenant'])->latest()->take(10)->get();

            return view('payments.landlord_index', compact(
                'payments',
                'pendingCount',
                'verifiedCount',
                'rejectedCount',
                'totalCollected',
                'allBills'
            ));
        }

        // Tenant view
        $unpaidBills = Bill::where('tenant_id', $user->id)
            ->whereIn('status', ['unpaid', 'pending_verification'])
            ->with('property')
            ->orderBy('due_date')
            ->get();

        $payments = Payment::where('tenant_id', $user->id)
            ->with(['property', 'bill'])
            ->latest()
            ->paginate(10);

        $bankDetails = $this->getBankDetails();
        $cryptoDetails = $this->getCryptoDetails();

        return view('payments.tenant_index', compact(
            'unpaidBills',
            'payments',
            'bankDetails',
            'cryptoDetails'
        ));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $selectedBillId = $request->query('bill_id');
        $selectedBill = null;

        if ($selectedBillId) {
            $selectedBill = Bill::where('tenant_id', $user->id)->find($selectedBillId);
        }

        $properties = Property::where('current_tenant_id', $user->id)->get();
        if ($properties->isEmpty()) {
            $properties = Property::all();
        }

        $unpaidBills = Bill::where('tenant_id', $user->id)
            ->where('status', 'unpaid')
            ->get();

        $billTypes = [
            'House Rent',
            'Electricity Bills',
            'Maintenance Charges',
            'Water Utility',
            'Security Levy',
            'Other',
        ];

        $paymentMethods = [
            'Bank Transfer',
            'Crypto (USDT TRC20)',
            'Crypto (USDT ERC20)',
            'Crypto (Bitcoin BTC)',
            'Crypto (Ethereum ETH)',
            'Debit/Credit Card',
            'Cash',
        ];

        $bankDetails = $this->getBankDetails();
        $cryptoDetails = $this->getCryptoDetails();

        return view('payments.create', compact(
            'properties',
            'unpaidBills',
            'selectedBill',
            'billTypes',
            'paymentMethods',
            'bankDetails',
            'cryptoDetails'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'bill_id' => ['nullable', 'exists:bills,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'bill_type' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string'],
            'transaction_reference' => ['required', 'string', 'max:255'],
            'payment_date' => ['required', 'date'],
            'receipt' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:5120'],
            'tenant_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'tenant_id' => $user->id,
            'property_id' => $validated['property_id'] ?? null,
            'bill_id' => $validated['bill_id'] ?? null,
            'bill_type' => $validated['bill_type'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'payment_date' => $validated['payment_date'],
            'receipt_image' => $receiptPath,
            'status' => 'pending',
            'tenant_notes' => $validated['tenant_notes'] ?? null,
        ]);

        // If linked to a bill, mark bill as pending_verification
        if (! empty($validated['bill_id'])) {
            Bill::where('id', $validated['bill_id'])->update([
                'status' => 'pending_verification',
            ]);
        }

        // Notify landlord(s)
        $landlords = User::where('role', 'landlord')->get();
        foreach ($landlords as $landlord) {
            SystemNotification::create([
                'user_id' => $landlord->id,
                'sender_id' => $user->id,
                'title' => 'New Payment Receipt Submitted',
                'message' => "Tenant {$user->name} submitted a payment of ₦".number_format($validated['amount'], 2)." for {$validated['bill_type']}.",
                'type' => 'payment',
                'link' => route('payments.show', $payment),
            ]);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment receipt submitted successfully! The Landlord will verify your payment shortly.');
    }

    public function show(Payment $payment): View
    {
        $user = auth()->user();

        // Tenants can only view their own payments
        if ($user->isTenant() && $payment->tenant_id !== $user->id) {
            abort(403);
        }

        $payment->load(['tenant', 'property', 'bill', 'verifier']);

        return view('payments.show', compact('payment'));
    }

    public function verify(Request $request, Payment $payment): RedirectResponse
    {
        if (! $request->user()->isLandlord()) {
            abort(403);
        }

        $payment->update([
            'status' => 'verified',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
            'admin_notes' => $request->input('admin_notes', 'Payment confirmed and verified.'),
        ]);

        // Mark associated bill as paid if exists
        if ($payment->bill_id) {
            $payment->bill->update(['status' => 'paid']);
        }

        // Notify tenant
        SystemNotification::create([
            'user_id' => $payment->tenant_id,
            'sender_id' => $request->user()->id,
            'title' => 'Payment Approved & Verified',
            'message' => 'Your payment of ₦'.number_format($payment->amount, 2)." for {$payment->bill_type} (Ref: {$payment->transaction_reference}) has been verified.",
            'type' => 'payment',
            'link' => route('payments.show', $payment),
        ]);

        return back()->with('success', 'Payment successfully verified!');
    }

    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        if (! $request->user()->isLandlord()) {
            abort(403);
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        // Reset linked bill to unpaid
        if ($payment->bill_id) {
            $payment->bill->update(['status' => 'unpaid']);
        }

        // Notify tenant
        SystemNotification::create([
            'user_id' => $payment->tenant_id,
            'sender_id' => $request->user()->id,
            'title' => 'Payment Receipt Rejected',
            'message' => 'Your payment of ₦'.number_format($payment->amount, 2)." for {$payment->bill_type} was rejected. Reason: {$request->input('admin_notes')}",
            'type' => 'payment',
            'link' => route('payments.show', $payment),
        ]);

        return back()->with('info', 'Payment rejected with note.');
    }

    public function createBill(): View
    {
        $properties = Property::with('currentTenant')->get();
        $tenants = User::where('role', 'tenant')->get();
        $billTypes = [
            'House Rent',
            'Electricity Bills',
            'Maintenance Charges',
            'Water Utility',
            'Security Levy',
            'Other',
        ];

        return view('payments.create_bill', compact('properties', 'tenants', 'billTypes'));
    }

    public function storeBill(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'tenant_id' => ['required', 'exists:users,id'],
            'bill_type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $bill = Bill::create($validated);

        // Notify tenant
        SystemNotification::create([
            'user_id' => $validated['tenant_id'],
            'sender_id' => $request->user()->id,
            'title' => 'New Bill Issued',
            'message' => "A new bill '{$validated['title']}' for ₦".number_format($validated['amount'], 2)." has been issued. Due: {$validated['due_date']}.",
            'type' => 'payment',
            'link' => route('payments.create', ['bill_id' => $bill->id]),
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Bill issued successfully to tenant!');
    }

    public function getBankDetails(): array
    {
        return [
            'bank_name' => 'Zenith International Bank PLC',
            'account_name' => 'Property System Escrow Services',
            'account_number' => '1029384756',
            'swift_code' => 'ZEIBNGLA',
            'branch' => 'Victoria Island Commercial Hub, Lagos',
            'instructions' => 'Include your full name and property name as the transfer narrative / remarks.',
        ];
    }

    public function getCryptoDetails(): array
    {
        return [
            'usdt_trc20' => [
                'name' => 'Tether USDT (TRC-20)',
                'address' => 'TLvY9p4LzQ3D8vQv5Z8mH3T9jX2R1s8W',
                'network' => 'TRON Network (TRC20)',
                'confirmations' => '1 confirmation',
            ],
            'usdt_erc20' => [
                'name' => 'Tether USDT (ERC-20)',
                'address' => '0x71C8366420AAb4174526154bB45129B4d1127bF6',
                'network' => 'Ethereum Mainnet (ERC20)',
                'confirmations' => '12 confirmations',
            ],
            'bitcoin' => [
                'name' => 'Bitcoin (BTC)',
                'address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'network' => 'Bitcoin Native SegWit',
                'confirmations' => '2 confirmations',
            ],
            'ethereum' => [
                'name' => 'Ethereum (ETH)',
                'address' => '0x71C8366420AAb4174526154bB45129B4d1127bF6',
                'network' => 'Ethereum Mainnet',
                'confirmations' => '12 confirmations',
            ],
        ];
    }
}
