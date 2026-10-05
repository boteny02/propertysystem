<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Landlord / Administrator
        $landlord = User::create([
            'name' => 'Alexander Vance (Landlord & Admin)',
            'email' => 'landlord@property.com',
            'password' => Hash::make('password'),
            'role' => 'landlord',
            'phone' => '+1 (555) 234-5678',
            'address' => 'Suite 500, Executive Plaza, Metropolis',
        ]);

        // 2. Create Tenants
        $tenant1 = User::create([
            'name' => 'Michael Chen',
            'email' => 'tenant@property.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+1 (555) 987-6543',
            'address' => 'Unit 402, Skyline Towers',
        ]);

        $tenant2 = User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@property.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+1 (555) 456-7890',
            'address' => 'Unit 12B, Emerald Residences',
        ]);

        $tenant3 = User::create([
            'name' => 'David Okonjo',
            'email' => 'david@property.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+1 (555) 345-6789',
            'address' => 'Villa 7, Palm Grove Heights',
        ]);

        // 3. Create Properties
        $prop1 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Skyline Luxury Penthouse 402',
            'slug' => 'skyline-luxury-penthouse-402',
            'location' => '142 Financial Boulevard, Downtown Core',
            'city' => 'Metropolis',
            'property_type' => 'Penthouse',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'rental_price' => 2800.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant1->id,
            'description' => 'A premier modern penthouse featuring panoramic skyline city views, private elevator access, floor-to-ceiling double-glazed soundproof glass, and an expansive wraparound terrace. Fully serviced with integrated smart-home automation.',
            'featured_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                '24/7 Electricity',
                'WiFi Internet',
                'Dedicated Parking',
                'Security / CCTV',
                'Swimming Pool',
                'Fitness Gym',
                'Air Conditioning',
                'Elevator',
                'Balcony / Patio',
                'Furnished',
            ],
        ]);

        $prop2 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Emerald Residences Suite 12B',
            'slug' => 'emerald-residences-suite-12b',
            'location' => '88 Garden District Avenue',
            'city' => 'Metropolis',
            'property_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'rental_price' => 1650.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant2->id,
            'description' => 'Charming 2-bedroom executive apartment in a peaceful, tree-lined gated enclave. Modern Italian-fitted kitchen with granite countertops, ensuite master bedroom, and energy-efficient climate control.',
            'featured_image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                '24/7 Electricity',
                'WiFi Internet',
                'Dedicated Parking',
                'Security / CCTV',
                'Air Conditioning',
                'Borehole Water',
                'Balcony / Patio',
            ],
        ]);

        $prop3 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Palm Grove Contemporary Villa 7',
            'slug' => 'palm-grove-contemporary-villa-7',
            'location' => '25 Coastal Crescent, Palm Bay',
            'city' => 'Palm Grove',
            'property_type' => 'Villa',
            'bedrooms' => 4,
            'bathrooms' => 4,
            'rental_price' => 4200.00,
            'rent_frequency' => 'monthly',
            'status' => 'rented',
            'current_tenant_id' => $tenant3->id,
            'description' => 'Magnificent detached 4-bedroom villa with private heated swimming pool, manicured private gardens, dual-car automated garage, and standalone solar power inverter backup.',
            'featured_image' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                '24/7 Electricity',
                'WiFi Internet',
                'Dedicated Parking',
                'Security / CCTV',
                'Swimming Pool',
                'Fitness Gym',
                'Air Conditioning',
                'Borehole Water',
                'Balcony / Patio',
                'Furnished',
            ],
        ]);

        $prop4 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Oakwood Terrace Duplex Unit A',
            'slug' => 'oakwood-terrace-duplex-unit-a',
            'location' => '104 Oakwood Heights Road',
            'city' => 'Metropolis',
            'property_type' => 'Duplex',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'rental_price' => 2100.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
            'current_tenant_id' => null,
            'description' => 'Spacious modern multi-level duplex featuring contemporary architectural lines, double-volume living area, customized walk-in wardrobes, and an open concept kitchen.',
            'featured_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                '24/7 Electricity',
                'Dedicated Parking',
                'Security / CCTV',
                'Air Conditioning',
                'Borehole Water',
                'Balcony / Patio',
            ],
        ]);

        $prop5 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Metro Loft Studio 305',
            'slug' => 'metro-loft-studio-305',
            'location' => '42 Innovation Parkway, Tech District',
            'city' => 'Metropolis',
            'property_type' => 'Studio',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'rental_price' => 950.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
            'current_tenant_id' => null,
            'description' => 'Ultra-chic urban studio designed for young professionals and digital creatives. Polished concrete floors, exposed brick accents, high-speed fiber connectivity, and rooftop communal lounge.',
            'featured_image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                'WiFi Internet',
                'Security / CCTV',
                'Air Conditioning',
                'Elevator',
                'Furnished',
                'Waste Disposal',
            ],
        ]);

        $prop6 = Property::create([
            'user_id' => $landlord->id,
            'name' => 'Cedar Crest Townhouse #4',
            'slug' => 'cedar-crest-townhouse-4',
            'location' => '71 Cedar Valley Lane',
            'city' => 'Suburban Valley',
            'property_type' => 'Townhouse',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'rental_price' => 1800.00,
            'rent_frequency' => 'monthly',
            'status' => 'available',
            'current_tenant_id' => null,
            'description' => 'Newly renovated 3-bedroom family townhouse located within walking distance to high-rated schools, parks, and retail centers. Includes a private fenced backyard and 2-vehicle driveway.',
            'featured_image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'facilities' => [
                'Dedicated Parking',
                'Security / CCTV',
                'Air Conditioning',
                'Borehole Water',
                'Balcony / Patio',
                'Waste Disposal',
            ],
        ]);

        // 4. Create Bills for Tenant 1 (Michael Chen)
        $bill1 = Bill::create([
            'property_id' => $prop1->id,
            'tenant_id' => $tenant1->id,
            'bill_type' => 'House Rent',
            'title' => 'House Rent (November 2026)',
            'amount' => 2800.00,
            'due_date' => now()->addDays(20)->toDateString(),
            'status' => 'unpaid',
            'notes' => 'Monthly lease payment for Skyline Luxury Penthouse 402.',
        ]);

        $bill2 = Bill::create([
            'property_id' => $prop1->id,
            'tenant_id' => $tenant1->id,
            'bill_type' => 'Electricity Bills',
            'title' => 'Monthly Electricity & Generator Service',
            'amount' => 185.50,
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => 'unpaid',
            'notes' => 'Meter reading: 45290 kWh to 45680 kWh.',
        ]);

        $bill3 = Bill::create([
            'property_id' => $prop1->id,
            'tenant_id' => $tenant1->id,
            'bill_type' => 'Maintenance Charges',
            'title' => 'Quarterly Estate Facility Maintenance',
            'amount' => 150.00,
            'due_date' => now()->subDays(5)->toDateString(),
            'status' => 'paid',
            'notes' => 'Covers pool upkeep, security guards, and communal gardening.',
        ]);

        // 5. Create Payments
        // Verified payment for bill 3
        Payment::create([
            'tenant_id' => $tenant1->id,
            'property_id' => $prop1->id,
            'bill_id' => $bill3->id,
            'bill_type' => 'Maintenance Charges',
            'amount' => 150.00,
            'payment_method' => 'Bank Transfer',
            'transaction_reference' => 'ZEN-TXN-84729104',
            'payment_date' => now()->subDays(6)->toDateString(),
            'receipt_image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
            'status' => 'verified',
            'tenant_notes' => 'Paid from Zenith Bank account ending in 4102.',
            'admin_notes' => 'Confirmed in escrow ledger.',
            'verified_by' => $landlord->id,
            'verified_at' => now()->subDays(5),
        ]);

        // Verified Rent Payment from last month
        Payment::create([
            'tenant_id' => $tenant1->id,
            'property_id' => $prop1->id,
            'bill_id' => null,
            'bill_type' => 'House Rent',
            'amount' => 2800.00,
            'payment_method' => 'Crypto (USDT TRC20)',
            'transaction_reference' => 'e7b1a20f9c4d6e8a0b3c5d7f9a1b3c5d7e9f1a3b5c7d9e1f3a5b7c9d1e3f5a7b',
            'payment_date' => now()->subDays(35)->toDateString(),
            'receipt_image' => 'https://images.unsplash.com/photo-1554224154-26032ffc0d07?auto=format&fit=crop&w=800&q=80',
            'status' => 'verified',
            'tenant_notes' => 'Sent 2,800 USDT TRC20 to landlord wallet.',
            'admin_notes' => 'On-chain transaction hash verified.',
            'verified_by' => $landlord->id,
            'verified_at' => now()->subDays(34),
        ]);

        // Pending Payment from Tenant 2 (Sarah)
        Payment::create([
            'tenant_id' => $tenant2->id,
            'property_id' => $prop2->id,
            'bill_id' => null,
            'bill_type' => 'Electricity Bills',
            'amount' => 140.00,
            'payment_method' => 'Bank Transfer',
            'transaction_reference' => 'CHASE-TRF-9920194',
            'payment_date' => now()->subDay()->toDateString(),
            'receipt_image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
            'status' => 'pending',
            'tenant_notes' => 'Direct wire transfer confirmation attached.',
            'admin_notes' => null,
        ]);

        // 6. Create Maintenance Requests
        MaintenanceRequest::create([
            'property_id' => $prop1->id,
            'tenant_id' => $tenant1->id,
            'title' => 'Master Bathroom Water Heater Pressure Drop',
            'category' => 'Plumbing',
            'urgency' => 'medium',
            'description' => 'The hot water flow rate in the master ensuite shower has significantly decreased over the last 3 days. The water heating works, but pressure is very low.',
            'photo' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
            'status' => 'in_progress',
            'technician_notes' => 'Plumber John Doe booked for inspection on Wednesday afternoon. Replacement cartridge ready.',
            'scheduled_date' => now()->addDays(2)->toDateString(),
        ]);

        MaintenanceRequest::create([
            'property_id' => $prop1->id,
            'tenant_id' => $tenant1->id,
            'title' => 'Living Room Air Conditioner Filter Service',
            'category' => 'HVAC / Air Conditioning',
            'urgency' => 'low',
            'description' => 'Routine filter cleaning and refrigerant check needed before summer heatwaves begin.',
            'photo' => null,
            'status' => 'resolved',
            'technician_notes' => 'Air conditioning filters replaced and coolant topped up. System cooling efficiently.',
            'scheduled_date' => now()->subDays(10)->toDateString(),
            'resolved_at' => now()->subDays(9),
        ]);

        MaintenanceRequest::create([
            'property_id' => $prop2->id,
            'tenant_id' => $tenant2->id,
            'title' => 'Balcony Exterior Light Fixture Flickering',
            'category' => 'Electrical',
            'urgency' => 'low',
            'description' => 'The weatherproof light on the balcony flickers when switched on. Might be a loose wiring contact or failing LED bulb.',
            'photo' => null,
            'status' => 'pending',
        ]);

        // 7. Create System Notifications
        SystemNotification::create([
            'user_id' => $tenant1->id,
            'sender_id' => $landlord->id,
            'title' => 'Maintenance Update: Plumber Dispatched',
            'message' => 'Your maintenance request "Master Bathroom Water Heater Pressure Drop" was marked In Progress. Technician scheduled for Wednesday.',
            'type' => 'maintenance',
            'link' => route('maintenance.index'),
            'read_at' => null,
        ]);

        SystemNotification::create([
            'user_id' => $tenant1->id,
            'sender_id' => $landlord->id,
            'title' => 'Payment Receipt Verified: ₦150.00',
            'message' => 'Your payment for Quarterly Estate Facility Maintenance (Ref: ZEN-TXN-84729104) has been approved and receipt issued.',
            'type' => 'payment',
            'link' => route('payments.index'),
            'read_at' => now()->subDays(5),
        ]);

        SystemNotification::create([
            'user_id' => $tenant1->id,
            'sender_id' => $landlord->id,
            'title' => 'Notice: Scheduled Generator Maintenance',
            'message' => 'Dear tenants, routine backup power generator servicing will take place this Saturday between 10:00 AM and 12:00 PM. Brief 15-minute switchovers may occur.',
            'type' => 'announcement',
            'link' => route('notifications.index'),
            'read_at' => null,
        ]);

        SystemNotification::create([
            'user_id' => $landlord->id,
            'sender_id' => $tenant2->id,
            'title' => 'New Payment Submitted by Sarah Jenkins',
            'message' => 'Tenant Sarah Jenkins submitted a payment receipt of ₦140.00 for Electricity Bills awaiting your review.',
            'type' => 'payment',
            'link' => route('payments.index'),
            'read_at' => null,
        ]);
    }
}
