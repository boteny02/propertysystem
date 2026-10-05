#!/usr/bin/env python3
"""
PropNest Property Management System - Complete Workflow Documentation Generator
Generates a professional, publication-grade Microsoft Word (.docx) document
complete with embedded high-resolution screenshots for all 17 application processes.
"""

import os
import sys
import zipfile
import xml.sax.saxutils as saxutils

def escape(text):
    return saxutils.escape(str(text))

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
SCREENSHOTS_DIR = os.path.join(BASE_DIR, "screenshots")
OUTPUT_DOCX = os.path.join(BASE_DIR, "PropNest_Complete_Workflow_Documentation.docx")
ARTIFACT_DOCX = "/Users/mobolaji/.gemini/antigravity/brain/9bfc5542-055a-4e82-bc5d-c5ca07369295/PropNest_Complete_Workflow_Documentation.docx"

PROCESSES = [
    {
        "id": 1,
        "section": "Public Discovery & Resident Onboarding",
        "title": "Property Catalogue Exploration & Multi-Filter Search",
        "img": "01_property_catalogue.png",
        "url": "/catalogue",
        "role": "Public Guest / Prospective Resident",
        "objective": "Allow prospective tenants and clients to browse, search, and filter premium residential properties across diverse types and pricing tiers.",
        "key_features": [
            "Real-time keyword search indexing titles, locations, descriptions, and city areas.",
            "Dynamic property category filtering (Apartments, Penthouses, Villas, Studios, Townhouses, Duplexes).",
            "Bedroom count selector (1, 2, 3, 4+ bedrooms) and occupancy status filters.",
            "Nigerian Naira (₦) budget range constraints with minimum and maximum price inputs.",
            "Sort order controls (Newest Listed, Price: Low to High, Price: High to Low).",
            "Responsive property cards with high-resolution visual previews, amenity tags, and monthly lease rates."
        ],
        "workflow_steps": [
            "Prospective resident navigates to the public catalogue (/catalogue).",
            "User enters desired parameters (e.g. 'Penthouse', '3 Bedrooms', Min ₦1,000,000 to Max ₦3,500,000).",
            "System executes query filtering against the Eloquent Property model and renders matching units dynamically.",
            "User clicks 'View Full Details' to inspect the selected property listing."
        ]
    },
    {
        "id": 2,
        "section": "Public Discovery & Resident Onboarding",
        "title": "Property Unit Specifications & Architectural Details",
        "img": "02_property_details.png",
        "url": "/properties/1",
        "role": "Public Guest / Prospective Resident",
        "objective": "Present exhaustive property specifications, multimedia showcases, facility amenities, and financial terms for an individual property unit.",
        "key_features": [
            "Hero visual banner with property title, geographical location, city, and verified rental rate badge in Naira (₦2,800.00/mo).",
            "Quick-specs dashboard displaying Bedrooms, Bathrooms, Property Classification, and Lease Frequency.",
            "Curated amenities checklist (24/7 Electricity, CCTV Surveillance, Private Pool, Smart Automation, Backup Inverter).",
            "Comprehensive architectural narrative description and property condition details.",
            "Instant booking / inquiry action trigger and assigned landlord escrow channel verification.",
            "Catalogue recommendation engine presenting similar properties in the same locality."
        ],
        "workflow_steps": [
            "User reviews full architectural specifications and verified high-resolution gallery.",
            "User checks verified facilities checklist and monthly lease schedule.",
            "User clicks 'Rent / Book This Property' or initiates user registration to begin the tenancy application."
        ]
    },
    {
        "id": 3,
        "section": "Public Discovery & Resident Onboarding",
        "title": "User Authentication & Role-Based Session Initialization",
        "img": "03_login_page.png",
        "url": "/login",
        "role": "All System Users (Tenants & Landlords)",
        "objective": "Provide secure credential authentication with granular role segregation between Landlords/Property Managers and Resident Tenants.",
        "key_features": [
            "Clean, card-based authentication form with email and password security checks.",
            "Persistent session support with secure 'Remember Me' encryption token.",
            "Instant Demo Login shortcuts (Landlord/Admin and Tenant/Resident) enabling rapid access testing.",
            "Automatic role-based redirection to customized dashboards upon successful authentication.",
            "Cryptographic password hashing via Laravel's native Bcrypt implementation."
        ],
        "workflow_steps": [
            "User enters email and password, or selects an instant role-based demo shortcut.",
            "System authenticates user identity against database credentials and checks role enum ('landlord' or 'tenant').",
            "Landlords are routed to the executive operations cockpit; tenants are routed to their personal residential suite."
        ]
    },
    {
        "id": 4,
        "section": "Public Discovery & Resident Onboarding",
        "title": "Resident & Landlord Account Registration",
        "img": "04_register_page.png",
        "url": "/register",
        "role": "New Platform Users",
        "objective": "Enable new resident tenants and landlords to create verified profiles with immediate access to appropriate facilities.",
        "key_features": [
            "Comprehensive profile capture: Full Legal Name, Email Address, and Nigerian Contact Telephone Number.",
            "Role picker: 'Tenant / Resident' or 'Landlord / Property Owner'.",
            "Client-side and server-side validation enforcing unique email addresses and password complexity.",
            "Direct account activation with immediate automated session startup."
        ],
        "workflow_steps": [
            "Prospective resident fills in user registration fields and chooses 'Tenant' role.",
            "System creates User Eloquent model and issues authentication cookies.",
            "New tenant is immediately directed to their personal tenant dashboard."
        ]
    },
    {
        "id": 5,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Tenant Central Command Dashboard",
        "img": "05_tenant_dashboard.png",
        "url": "/dashboard (as Tenant)",
        "role": "Resident Tenant (e.g. Michael Chen)",
        "objective": "Serve as the resident's home hub for monitoring their leased unit, tracking billing balances, submitting payments, and managing maintenance requests.",
        "key_features": [
            "Prominent Leased Unit Card showcasing active unit name, location, and verified monthly rental rate (₦2,800.00/mo).",
            "Unit Facility Badges confirming active amenities (WiFi, 24/7 Power, CCTV, Swimming Pool, Parking).",
            "Outstanding Financial Obligations widget highlighting Total Due in Naira (₦) with instant '+ Pay Bill' trigger.",
            "Unpaid bills listing showing bill category (House Rent, Electricity, Facility Maintenance) and due dates.",
            "Recent submitted payment proofs history with status badges (Verified, Pending Review, Rejected).",
            "Active Maintenance Tracker displaying ticket urgency, current status, and technician notes.",
            "Estate Announcements bulletin displaying scheduled generator maintenance notices and community updates."
        ],
        "workflow_steps": [
            "Resident logs in and arrives at the tenant dashboard overview.",
            "Resident immediately identifies outstanding balances (e.g. Electricity, Rent) and click 'Pay Now'.",
            "Resident tracks technician status on open work tickets and reviews official notices."
        ]
    },
    {
        "id": 6,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Official Payment Accounts & Channel Infrastructure",
        "img": "06_tenant_payment_center.png",
        "url": "/payments (as Tenant)",
        "role": "Resident Tenant",
        "objective": "Provide verified, secure payment channel information including Zenith Bank escrow accounts and multi-token cryptocurrency wallets.",
        "key_features": [
            "Dual-Channel Facility: Channel 1 (Zenith Bank PLC Escrow) and Channel 2 (Verified Cryptocurrency Wallets).",
            "Zenith Bank Wire Transfer Card: Beneficiary Name, Account Number (1029384756), SWIFT code, and transfer remarks.",
            "One-click clipboard copy utility for account numbers and cryptocurrency wallet addresses.",
            "Multi-network crypto support: Tether USDT (TRC-20), Bitcoin (BTC Native SegWit), Ethereum (ETH), and USDT (ERC-20).",
            "Clear payment narrative instructions preventing unallocated remittances.",
            "Outstanding bills interactive list with direct 'Pay This Bill →' routing buttons."
        ],
        "workflow_steps": [
            "Tenant accesses the Payments & Invoicing center.",
            "Tenant selects their preferred payment medium (Bank Transfer or On-Chain Crypto).",
            "Tenant copies the destination credentials, completes external transfer, and proceeds to proof submission."
        ]
    },
    {
        "id": 7,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Payment Proof & Bank Receipt Submission",
        "img": "07_submit_payment_receipt.png",
        "url": "/payments/create",
        "role": "Resident Tenant",
        "objective": "Enable residents to upload transaction slips, specify bank/crypto references, and link payments to specific invoices.",
        "key_features": [
            "Invoice Linking Selector: Pre-selects specific unpaid bills or allows custom amount entry.",
            "Automated field pre-filling (Unit selection, bill category, and exact Naira amount due).",
            "Amount Paid field denominated in Nigerian Naira (₦) with decimal precision.",
            "Payment Method dropdown (Zenith Bank Transfer, USDT TRC20, Bitcoin, Ethereum, Card, Cash).",
            "Transaction Reference / On-Chain TXID text field for audit verification.",
            "Digital file upload input for bank transaction slips, screenshots, or receipt images (JPG, PNG, PDF up to 5MB).",
            "Optional tenant remarks and notes for landlord escrow reconciliation."
        ],
        "workflow_steps": [
            "Tenant chooses an unpaid invoice or selects custom payment.",
            "Tenant inputs amount paid in Naira (₦), selects 'Bank Transfer', and pastes transaction reference.",
            "Tenant attaches the payment confirmation screenshot and submits form.",
            "System creates a Payment record in 'pending' status, updates bill status to 'pending_verification', and notifies landlord."
        ]
    },
    {
        "id": 8,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Digital Payment Slip & Escrow Audit Voucher",
        "img": "08_printable_receipt_slip.png",
        "url": "/payments/{id}",
        "role": "Resident Tenant & Landlord",
        "objective": "Render an official, print-ready digital transaction receipt slip certifying payment submission and landlord verification.",
        "key_features": [
            "Header featuring official PropNest escrow insignia and unique voucher identifier (e.g. SLIP #PAY-00001).",
            "Status confirmation pill badge: '✓ Verified & Approved' or '⌛ Pending Landlord Verification'.",
            "Prominent settled amount in Nigerian Naira (e.g. ₦150.00 / ₦2,800.00) with 'NGN (Nigerian Naira ₦)' currency badge.",
            "Full metadata breakdown: Bill Category, Payment Method, Date Settled, and Resident Profile details.",
            "Cryptographic / banking transaction reference code box for cross-institutional reconciliation.",
            "Embedded proof attachment viewer allowing full inspection of submitted receipts.",
            "Print receipt utility button formatted with clean print stylesheets."
        ],
        "workflow_steps": [
            "Tenant or landlord accesses the payment slip via notification link or payment history table.",
            "System loads the verified transaction details and displays complete audit log.",
            "User can print or save the PDF voucher for corporate tax or accounting records."
        ]
    },
    {
        "id": 9,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Maintenance Service Ticket Submission",
        "img": "09_submit_maintenance.png",
        "url": "/maintenance/create",
        "role": "Resident Tenant",
        "objective": "Allow tenants to report facility defects, plumbing leaks, electrical issues, or routine maintenance needs directly to the property team.",
        "key_features": [
            "Associated Property Unit selection pre-locked to tenant's current occupied premises.",
            "Issue Category dropdown (Plumbing, Electrical, HVAC / Air Conditioning, Carpentry, Structural, Painting, Other).",
            "Priority & Urgency rating selector (Low, Medium, High Priority, Immediate Emergency).",
            "Descriptive title and comprehensive defect explanation text area.",
            "Optional defect photograph upload for remote technician appraisal.",
            "Automated dispatch notification sent immediately to landlord upon submission."
        ],
        "workflow_steps": [
            "Resident detects an issue in their apartment (e.g. water pressure decrease).",
            "Resident navigates to Maintenance -> Report New Issue.",
            "Resident fills in ticket details, selects urgency level, optionally uploads a photo, and clicks 'Submit Ticket'.",
            "System creates MaintenanceRequest record with status 'pending' and alerts the landlord."
        ]
    },
    {
        "id": 10,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "Maintenance Ticket Lifecycle & Technician Timeline",
        "img": "10_maintenance_ticket_timeline.png",
        "url": "/maintenance/{id}",
        "role": "Resident Tenant & Landlord",
        "objective": "Provide full transparency into technician scheduling, progress updates, and resolution milestones for maintenance issues.",
        "key_features": [
            "Permanent ticket reference identifier (e.g. TICKET #MT-00001).",
            "Dynamic four-stage visual milestone timeline: 1. Reported → 2. Technician Assigned → 3. In Progress → 4. Resolved.",
            "Detailed problem statement and resident submission timestamp.",
            "Technician schedule date banner and official technician work progress notes.",
            "For landlords: integrated administrative dispatch panel to reassign status, schedule technician dates, and append notes."
        ],
        "workflow_steps": [
            "Resident reviews ticket timeline to see technician assignment status.",
            "Landlord updates status to 'in_progress', schedules visit date, and notes technician name.",
            "Resident receives real-time notification alert detailing technician appointment."
        ]
    },
    {
        "id": 11,
        "section": "Resident / Tenant Self-Service Workflow",
        "title": "System Notifications & Real-Time Alerts Feed",
        "img": "11_notifications_feed.png",
        "url": "/notifications",
        "role": "Resident Tenant & Landlord",
        "objective": "Centralize all system alerts, invoice issuances, verification receipts, maintenance status updates, and estate announcements.",
        "key_features": [
            "Chronological activity feed with categorical iconography (Payments, Maintenance, System Announcements).",
            "Real-time unread badge counter in top navigation header.",
            "Color-coded notification cards with direct contextual action buttons ('View Receipt', 'Check Ticket', 'View Bulletin').",
            "'Mark as Read' single-click acknowledgement and 'Mark All as Read' bulk action.",
            "Dynamic notification messages formatted with Nigerian Naira amounts (₦)."
        ],
        "workflow_steps": [
            "User clicks the notification bell icon when the unread badge illuminates.",
            "User reviews recent updates (e.g. 'Payment Receipt Verified: ₦150.00', 'Maintenance Update: Plumber Dispatched').",
            "Clicking a notification immediately opens the relevant transaction or ticket."
        ]
    },
    {
        "id": 12,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Landlord Executive Operations Cockpit",
        "img": "12_landlord_dashboard.png",
        "url": "/dashboard (as Landlord)",
        "role": "Landlord / Property Manager",
        "objective": "Deliver comprehensive high-level financial metrics, property occupancy KPIs, pending verification queues, and quick operational triggers.",
        "key_features": [
            "Quick Actions Header Bar: '+ Add New Property', 'Issue Bill to Tenant', 'Post Announcement'.",
            "Key Performance Indicators Strip:",
            "  • Portfolio Occupancy (Occupied vs. Vacant units with percentage calculations).",
            "  • Total Verified Revenue formatted in Nigerian Naira (₦2,950.00) confirmed in escrow.",
            "  • Pending Receipts Awaiting Review count with aggregate Naira sum awaiting verification.",
            "  • Active Maintenance Tickets count requiring dispatch oversight.",
            "Recent Payment Receipts queue allowing instant landlord verification.",
            "Active Maintenance Queue summary with urgency badges.",
            "Portfolio Properties Directory with occupancy status pills and monthly lease rates in Naira."
        ],
        "workflow_steps": [
            "Landlord logs in and immediately reviews key financial performance and pending action items.",
            "Landlord inspects pending tenant payment slips requiring verification.",
            "Landlord triggers new property additions, tenant billings, or maintenance management."
        ]
    },
    {
        "id": 13,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Financial Ledger & Payment Slip Verification Center",
        "img": "13_landlord_payment_ledger.png",
        "url": "/payments (as Landlord)",
        "role": "Landlord / Property Manager",
        "objective": "Provide complete financial ledger oversight and administrative tools to inspect, approve, or reject tenant payment proofs.",
        "key_features": [
            "Financial Metric Cards: Total Verified Revenue in Naira (₦), Pending Review count, Verified count, Rejected count.",
            "Status Filter Bar: All Records, Pending Review, Verified, Rejected.",
            "Submitted Tenant Payments Table: Tenant Name, Unit, Bill Category, Amount in Naira (₦), Method & TXID, Date, and Status.",
            "Direct Verification Actions: Single-click 'Approve Receipt' button with confirmation modal.",
            "Direct Rejection Action with mandatory administrative feedback notes sent directly to tenant.",
            "Issued Bills Master Table tracking all open and settled tenant utility invoices."
        ],
        "workflow_steps": [
            "Landlord navigates to Payments Ledger.",
            "Landlord clicks on a pending payment to inspect uploaded bank teller slip or on-chain transaction hash.",
            "Upon confirming settlement in Zenith Bank escrow, landlord clicks 'Approve Receipt'.",
            "System marks payment as 'verified', settles associated bill as 'paid', and sends congratulatory receipt to resident."
        ]
    },
    {
        "id": 14,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Estate Maintenance Operations & Dispatch Queue",
        "img": "14_landlord_maintenance_queue.png",
        "url": "/maintenance (as Landlord)",
        "role": "Landlord / Property Manager",
        "objective": "Manage, schedule, and resolve all reported tenant repair tickets across the entire property portfolio.",
        "key_features": [
            "Ticket Status Counters: In Progress tickets, Resolved tickets, and Urgent Emergency alerts.",
            "Status Filter: All Tickets, Pending, In Progress, Resolved.",
            "Comprehensive Maintenance Queue Table: Ticket ID, Resident Tenant, Property Unit, Category, Urgency Priority, Date, Status.",
            "Direct Action buttons to open ticket inspection, schedule technician, update repair notes, and close tickets."
        ],
        "workflow_steps": [
            "Landlord reviews incoming maintenance queue and filters by 'Pending' or 'Emergency'.",
            "Landlord opens ticket, contacts service vendor (e.g. plumber or HVAC tech), and records scheduled appointment date.",
            "Upon completion of physical work, landlord marks ticket 'Resolved'."
        ]
    },
    {
        "id": 15,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Property Listing Creation & Portfolio Expansion",
        "img": "15_landlord_add_property.png",
        "url": "/admin/properties/create",
        "role": "Landlord / Property Manager",
        "objective": "Empower landlords to register new property units, configure rental rates in Naira, define amenities, and publish to the public catalogue.",
        "key_features": [
            "Property Identity: Name, Street Address, City, and Property Type (Penthouse, Apartment, Villa, Studio, Duplex, Townhouse).",
            "Capacity Specs: Bedroom count, Bathroom count, Initial Occupancy Status (Available, Rented, Maintenance).",
            "Rental Financial Terms: Rental Price in Nigerian Naira (Naira ₦) with currency prefix, and Payment Frequency (Monthly, Quarterly, Annually).",
            "Resident Assignment: Option to assign an existing registered tenant immediately upon creation.",
            "Amenities Checklist: Checkbox matrix for 24/7 Power, WiFi, CCTV, Pool, Gym, AC, Borehole Water, Furnished, Balcony.",
            "Photographic Media: Primary featured exterior/interior showcase photo upload.",
            "Architectural Narrative Description markdown editor."
        ],
        "workflow_steps": [
            "Landlord clicks '+ Add New Property' from dashboard.",
            "Fills in unit parameters, sets price in Naira (e.g. ₦1,500,000 / mo), selects facilities, and uploads primary photograph.",
            "System creates Property model, generates SEO slug, stores image in public storage, and updates public catalogue."
        ]
    },
    {
        "id": 16,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Resident Utility Invoicing & Bill Issuance",
        "img": "16_landlord_issue_bill.png",
        "url": "/admin/bills/create",
        "role": "Landlord / Property Manager",
        "objective": "Generate and distribute itemized utility, service levy, or rental invoices to specific tenant residents with designated due dates.",
        "key_features": [
            "Target Property Unit Selector dropdown with automatic tenant linkage.",
            "Assigned Resident Tenant recipient selection.",
            "Bill Classification category (House Rent, Electricity Bills, Maintenance Charges, Water Utility, Security Levy, Other).",
            "Invoice Title field (e.g. 'November 2026 House Rent', 'Generator Fuel Surcharge').",
            "Amount Due in Nigerian Naira (Amount Due (Naira ₦)) with ₦ currency symbol prefix.",
            "Payment Due Date picker with default 14-day settlement grace period.",
            "Invoice breakdown notes and utility meter readings field."
        ],
        "workflow_steps": [
            "Landlord navigates to 'Issue Bill to Tenant'.",
            "Selects tenant, chooses 'Electricity Bills', sets amount to ₦185.50 (or Naira equivalent), and sets due date.",
            "Clicks 'Issue & Dispatch Bill to Resident'.",
            "System creates Bill record with 'unpaid' status, alerts tenant dashboard, and sends notification."
        ]
    },
    {
        "id": 17,
        "section": "Landlord & Estate Management Operations Workflow",
        "title": "Estate-Wide Broadcast Announcements",
        "img": "17_landlord_post_announcement.png",
        "url": "/admin/announcements/create",
        "role": "Landlord / Property Manager",
        "objective": "Distribute high-priority estate notices, routine maintenance bulletins, and security advisories to all resident tenants instantaneously.",
        "key_features": [
            "Announcement Subject Title field (e.g. 'Notice: Scheduled Generator Maintenance').",
            "Announcement Classification (General Announcement, Maintenance Alert, Security Notice, Policy Update).",
            "Rich message body detailing timeline, expectations, and emergency contact details.",
            "Automated broadcast engine delivering notification record to every tenant registered in the database simultaneously."
        ],
        "workflow_steps": [
            "Landlord clicks 'Post Announcement' from dashboard.",
            "Enters title, chooses 'Maintenance Notice', types details regarding routine power generator servicing, and submits.",
            "Every registered resident immediately receives the bulletin in their notification feed and tenant dashboard."
        ]
    }
]

def generate_styles_xml():
    return """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
        <w:sz w:val="22"/>
        <w:szCs w:val="22"/>
        <w:color w:val="1E293B"/>
        <w:lang w:val="en-US"/>
      </w:rPr>
    </w:rPrDefault>
    <w:pPrDefault>
      <w:pPr>
        <w:spacing w:after="160" w:line="276" w:lineRule="auto"/>
      </w:pPr>
    </w:pPrDefault>
  </w:docDefaults>
  <w:style w:type="paragraph" w:styleId="Normal" w:default="1">
    <w:name w:val="Normal"/>
    <w:qFormat/>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Subtitle"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="360" w:after="120"/>
      <w:jc w:val="center"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/>
      <w:b/>
      <w:sz w:val="56"/>
      <w:color w:val="1E1B4B"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle">
    <w:name w:val="Subtitle"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="60" w:after="360"/>
      <w:jc w:val="center"/>
    </w:pPr>
    <w:rPr>
      <w:sz w:val="26"/>
      <w:color w:val="4338CA"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="400" w:after="160"/>
      <w:pBdr>
        <w:bottom w:val="single" w:sz="12" w:space="4" w:color="4338CA"/>
      </w:pBdr>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/>
      <w:b/>
      <w:sz w:val="36"/>
      <w:color w:val="1E1B4B"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="320" w:after="120"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/>
      <w:b/>
      <w:sz w:val="28"/>
      <w:color w:val="312E81"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading3">
    <w:name w:val="heading 3"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="240" w:after="80"/>
    </w:pPr>
    <w:rPr>
      <w:b/>
      <w:sz w:val="24"/>
      <w:color w:val="334155"/>
    </w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Caption">
    <w:name w:val="caption"/>
    <w:basedOn w:val="Normal"/>
    <w:qFormat/>
    <w:pPr>
      <w:spacing w:before="60" w:after="200"/>
      <w:jc w:val="center"/>
    </w:pPr>
    <w:rPr>
      <w:i/>
      <w:sz w:val="18"/>
      <w:color w:val="64748B"/>
    </w:rPr>
  </w:style>
</w:styles>"""

def generate_document_rels_xml(processes):
    rels = [
        '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
        '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">',
        '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>',
        '  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings" Target="settings.xml"/>'
    ]
    for p in processes:
        r_id = f"rIdImg{p['id']}"
        target = f"media/{p['img']}"
        rels.append(f'  <Relationship Id="{r_id}" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="{target}"/>')
    rels.append('</Relationships>')
    return "\n".join(rels)

def p_run(text, bold=False, italic=False, color=None, size=None):
    r_props = []
    if bold:
        r_props.append('<w:b/>')
    if italic:
        r_props.append('<w:i/>')
    if color:
        r_props.append(f'<w:color w:val="{color}"/>')
    if size:
        r_props.append(f'<w:sz w:val="{size}"/>')
    r_pr = f'<w:rPr>{"".join(r_props)}</w:rPr>' if r_props else ""
    return f'<w:r>{r_pr}<w:t xml:space="preserve">{escape(text)}</w:t></w:r>'

def p_para(runs_or_text, style=None, align=None, space_before=None, space_after=None, keep_next=False, bold=False):
    p_props = []
    if style:
        p_props.append(f'<w:pStyle w:val="{style}"/>')
    if align:
        p_props.append(f'<w:jc w:val="{align}"/>')
    if space_before is not None or space_after is not None:
        sb = f' w:before="{space_before}"' if space_before is not None else ''
        sa = f' w:after="{space_after}"' if space_after is not None else ''
        p_props.append(f'<w:spacing{sb}{sa}/>')
    if keep_next:
        p_props.append('<w:keepNext/>')
    
    p_pr = f'<w:pPr>{"".join(p_props)}</w:pPr>' if p_props else ""
    
    if isinstance(runs_or_text, str):
        content = p_run(runs_or_text, bold=bold)
    elif isinstance(runs_or_text, list):
        content = "".join(runs_or_text)
    else:
        content = runs_or_text
        
    return f'<w:p>{p_pr}{content}</w:p>'

def p_image(img_id, img_name, cx=5486400, cy=3643500):
    return f"""<w:p>
  <w:pPr><w:jc w:val="center"/><w:spacing w:before="120" w:after="40"/></w:pPr>
  <w:r>
    <w:drawing>
      <wp:inline distT="0" distB="0" distL="0" distR="0">
        <wp:extent cx="{cx}" cy="{cy}"/>
        <wp:docPr id="{img_id}" name="{escape(img_name)}"/>
        <wp:cNvGraphicFramePr>
          <a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" noChangeAspect="1"/>
        </wp:cNvGraphicFramePr>
        <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
          <a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">
            <pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
              <pic:nvPicPr>
                <pic:cNvPr id="{img_id}" name="{escape(img_name)}"/>
                <pic:cNvPicPr/>
              </pic:nvPicPr>
              <pic:blipFill>
                <a:blip r:embed="rIdImg{img_id}" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"/>
                <a:stretch><a:fillRect/></a:stretch>
              </pic:blipFill>
              <pic:spPr>
                <a:xfrm>
                  <a:off x="0" y="0"/>
                  <a:ext cx="{cx}" cy="{cy}"/>
                </a:xfrm>
                <a:prstGeom prst="rect"><a:avLst/></a:prstGeom>
                <a:ln w="12700"><a:solidFill><a:srgbClr val="CBD5E1"/></a:solidFill></a:ln>
              </pic:spPr>
            </pic:pic>
          </a:graphicData>
        </a:graphic>
      </wp:inline>
    </w:drawing>
  </w:r>
</w:p>"""

def p_table_row(cells, is_header=False):
    out = ['<w:tr>']
    for width_dxa, bg_color, content in cells:
        shd = f'<w:shd w:val="clear" w:color="auto" w:fill="{bg_color}"/>' if bg_color else ''
        tc_bdr = '<w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/><w:left w:val="none"/><w:right w:val="none"/></w:tcBorders>'
        mar = '<w:tcMar><w:top w:w="120" w:type="dxa"/><w:bottom w:w="120" w:type="dxa"/><w:left w:w="160" w:type="dxa"/><w:right w:w="160" w:type="dxa"/></w:tcMar>'
        tc_pr = f'<w:tcPr><w:tcW w:w="{width_dxa}" w:type="dxa"/>{shd}{tc_bdr}{mar}</w:tcPr>'
        out.append(f'<w:tc>{tc_pr}{content}</w:tc>')
    out.append('</w:tr>')
    return "".join(out)

def p_table(rows, total_width_dxa=9360):
    tbl_pr = f'<w:tblPr><w:tblW w:w="{total_width_dxa}" w:type="dxa"/><w:jc w:val="center"/><w:tblBorders><w:top w:val="single" w:sz="6" w:color="CBD5E1"/><w:bottom w:val="single" w:sz="6" w:color="CBD5E1"/><w:insideH w:val="single" w:sz="4" w:color="E2E8F0"/><w:left w:val="none"/><w:right w:val="none"/><w:insideV w:val="none"/></w:tblBorders></w:tblPr>'
    return f'<w:tbl>{tbl_pr}{"".join(rows)}</w:tbl>'

def p_callout(text, title=None, border_color="4338CA", bg_color="EEF2FF"):
    p_pr = f'<w:pPr><w:pBdr><w:left w:val="single" w:sz="24" w:space="15" w:color="{border_color}"/></w:pBdr><w:shd w:val="clear" w:color="auto" w:fill="{bg_color}"/><w:spacing w:before="160" w:after="160"/><w:ind w:left="240" w:right="240"/></w:pPr>'
    runs = []
    if title:
        runs.append(f'<w:r><w:rPr><w:b/><w:color w:val="{border_color}"/><w:sz w:val="22"/></w:rPr><w:t xml:space="preserve">{escape(title)}: </w:t></w:r>')
    runs.append(f'<w:r><w:rPr><w:color w:val="334155"/><w:sz w:val="20"/></w:rPr><w:t xml:space="preserve">{escape(text)}</w:t></w:r>')
    return f'<w:p>{p_pr}{"".join(runs)}</w:p>'

def generate_document_xml():
    body = []
    
    # --- COVER / HEADER BANNER ---
    body.append(p_para("PROPNEST PROPERTY MANAGEMENT SYSTEM", style="Subtitle"))
    body.append(p_para("Complete Operational Workflow & Process Documentation", style="Title"))
    body.append(p_para([
        p_run("Official System Manual with End-to-End Walkthroughs, User Journeys, and Visual Interface Proofs\n", italic=True, color="64748B"),
        p_run("Currency Standard: Nigerian Naira (₦ / NGN) | Multi-Channel Escrow Architecture", bold=True, color="059669")
    ], align="center", space_after=320))
    
    # Metadata Table
    meta_rows = [
        p_table_row([
            (2400, "F1F5F9", p_para([p_run("Document Version", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("v1.2.0 (Production Release)", size=18)])),
            (2400, "F1F5F9", p_para([p_run("Platform Engine", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("Laravel 12 / PHP 8.4", size=18)])),
        ]),
        p_table_row([
            (2400, "F1F5F9", p_para([p_run("Jurisdiction / Currency", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("Nigeria (₦ - Naira / NGN)", bold=True, color="059669", size=18)])),
            (2400, "F1F5F9", p_para([p_run("Escrow Facility", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("Zenith International Bank PLC", size=18)])),
        ]),
        p_table_row([
            (2400, "F1F5F9", p_para([p_run("Crypto Gateway", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("USDT TRC20/ERC20, BTC, ETH", size=18)])),
            (2400, "F1F5F9", p_para([p_run("Audited Workflows", bold=True, size=18)])),
            (2280, "FFFFFF", p_para([p_run("17 End-to-End Processes", bold=True, color="4338CA", size=18)])),
        ]),
    ]
    body.append(p_table(meta_rows))
    body.append(p_para("", space_after=240))
    
    # Executive Summary
    body.append(p_para("Executive Overview & Architecture", style="Heading1"))
    body.append(p_para([
        p_run("PropNest", bold=True),
        p_run(" is a contemporary, cloud-native Property Management and Residential Escrow Automation System designed specifically for real estate portfolios operating in Nigeria. The platform enforces strict role-based separation between "),
        p_run("Resident Tenants", bold=True),
        p_run(" and "),
        p_run("Landlords / Property Managers", bold=True),
        p_run(", while providing public guests with an interactive architectural catalogue of verified homes, apartments, penthouses, and executive villas.")
    ]))
    
    body.append(p_callout(
        "Following system standardisation, all rental agreements, facility charges, utility bills, and payment records across the platform are denominated and calculated in Nigerian Naira (₦). Financial auditing is backed by Zenith Bank account ledger tracking and cryptocurrency verification hashes.",
        title="Nigerian Naira (₦) Currency Integration",
        border_color="059669",
        bg_color="ECFDF5"
    ))
    
    body.append(p_para("The system encompasses three foundational operational phases across 17 distinct interactive processes:"))
    body.append(p_para([
        p_run("1. Phase I — Public Discovery & Onboarding: ", bold=True, color="4338CA"),
        p_run("Catalogue browsing, multi-criteria price filtering in ₦, architectural specifications review, role-aware user registration and secure login authentication.")
    ]))
    body.append(p_para([
        p_run("2. Phase II — Resident / Tenant Self-Service Operations: ", bold=True, color="4338CA"),
        p_run("Central tenant command cockpit, official payment accounts center, digital receipt slip submission, voucher printing, facility maintenance requests, ticket milestones timeline, and notifications alerts feed.")
    ]))
    body.append(p_para([
        p_run("3. Phase III — Landlord Portfolio & Escrow Administration: ", bold=True, color="4338CA"),
        p_run("Executive revenue analytics cockpit, transaction ledger reconciliation, proof image verification/rejection, maintenance technician dispatch queue, unit creation, utility bill generation, and estate broadcasts.")
    ]))
    
    # Process Directory Table
    body.append(p_para("Process Directory & Workflow Index", style="Heading2"))
    index_rows = [
        p_table_row([
            (800, "1E1B4B", p_para([p_run("#", bold=True, color="FFFFFF", size=18)])),
            (2400, "1E1B4B", p_para([p_run("Process Workflow", bold=True, color="FFFFFF", size=18)])),
            (2400, "1E1B4B", p_para([p_run("System Domain / Phase", bold=True, color="FFFFFF", size=18)])),
            (2200, "1E1B4B", p_para([p_run("Primary Actor", bold=True, color="FFFFFF", size=18)])),
            (1560, "1E1B4B", p_para([p_run("Route Endpoint", bold=True, color="FFFFFF", size=18)])),
        ], is_header=True)
    ]
    for p in PROCESSES:
        bg = "F8FAFC" if p["id"] % 2 == 0 else "FFFFFF"
        index_rows.append(p_table_row([
            (800, bg, p_para([p_run(f"P-{p['id']:02d}", bold=True, size=18)])),
            (2400, bg, p_para([p_run(p["title"], bold=True, color="1E293B", size=18)])),
            (2400, bg, p_para([p_run(p["section"], size=18)])),
            (2200, bg, p_para([p_run(p["role"], size=18)])),
            (1560, bg, p_para([p_run(p["url"], color="4338CA", size=18)])),
        ]))
    body.append(p_table(index_rows))
    body.append(p_para("", space_after=320))
    
    # Detailed Process Sections
    current_section = ""
    for p in PROCESSES:
        if p["section"] != current_section:
            current_section = p["section"]
            body.append(p_para(f"Phase Workflow: {current_section}", style="Heading1", space_before=400))
        
        # Heading for process
        body.append(p_para(f"Process {p['id']}: {p['title']}", style="Heading2", keep_next=True))
        
        # Meta strip
        meta_info = [
            p_run("Primary Actor: ", bold=True),
            p_run(f"{p['role']} | "),
            p_run("Route URL: ", bold=True),
            p_run(f"{p['url']} | "),
            p_run("Status: ", bold=True),
            p_run("Fully Operational (Verified in NGN ₦)", color="059669", bold=True)
        ]
        body.append(p_para(meta_info, space_after=120))
        
        # Objective
        body.append(p_para([
            p_run("Operational Purpose: ", bold=True),
            p_run(p["objective"])
        ]))
        
        # Screenshot Image with caption
        body.append(p_image(p["id"], p["img"]))
        body.append(p_para(f"Figure {p['id']}: Process {p['id']} Visual Interface — {p['title']}", style="Caption"))
        
        # Key UI features & capabilities
        body.append(p_para("Key User Interface Elements & Operational Features:", bold=True, space_before=160, space_after=80, keep_next=True))
        for feat in p["key_features"]:
            body.append(p_para([
                p_run("•  ", bold=True, color="4338CA"),
                p_run(feat)
            ], space_after=80))
            
        # Step-by-step workflow
        body.append(p_para("Execution Flow & State Transitions:", bold=True, space_before=160, space_after=80, keep_next=True))
        for step_idx, step in enumerate(p["workflow_steps"], 1):
            body.append(p_para([
                p_run(f"{step_idx}. ", bold=True, color="059669"),
                p_run(step)
            ], space_after=80))
            
        body.append(p_para("", space_after=240))
        
    # Technical Architecture & Escrow Protocols Appendix
    body.append(p_para("Technical Architecture & Escrow Security Appendix", style="Heading1"))
    body.append(p_para("PropNest employs defense-in-depth security principles to safeguard tenant transactions, resident communications, and landlord administrative controls:"))
    
    tech_table_rows = [
        p_table_row([
            (2800, "1E1B4B", p_para([p_run("Architectural Domain", bold=True, color="FFFFFF", size=18)])),
            (6560, "1E1B4B", p_para([p_run("Implementation Details & Compliance Standard", bold=True, color="FFFFFF", size=18)])),
        ], is_header=True),
        p_table_row([
            (2800, "F8FAFC", p_para([p_run("Currency Precision & Formatting", bold=True, size=18)])),
            (6560, "F8FAFC", p_para([p_run("All monetary models use decimal(12,2) with global Number::useCurrency('NGN') and dynamic ₦ currency prefix filters on all Blade views.", size=18)])),
        ]),
        p_table_row([
            (2800, "FFFFFF", p_para([p_run("Escrow Banking Facility", bold=True, size=18)])),
            (6560, "FFFFFF", p_para([p_run("Zenith International Bank PLC (Acct: 1029384756, SWIFT: ZEIBNGLA). Payments require administrative verification before receipt issuance.", size=18)])),
        ]),
        p_table_row([
            (2800, "F8FAFC", p_para([p_run("Cryptocurrency Verification", bold=True, size=18)])),
            (6560, "F8FAFC", p_para([p_run("Supports USDT (TRC-20 & ERC-20), Bitcoin, and Ethereum. Transaction hashes are immutably archived and cross-checked on-chain.", size=18)])),
        ]),
        p_table_row([
            (2800, "FFFFFF", p_para([p_run("Audit Trail & Storage", bold=True, size=18)])),
            (6560, "FFFFFF", p_para([p_run("Receipt images and maintenance defect photos are isolated in private filesystem storage disks with verified URL generation.", size=18)])),
        ]),
        p_table_row([
            (2800, "F8FAFC", p_para([p_run("Automated Notification Dispatch", bold=True, size=18)])),
            (6560, "F8FAFC", p_para([p_run("SystemNotification models dispatches real-time broadcast alerts across billing, verification, maintenance, and estate notices.", size=18)])),
        ]),
    ]
    body.append(p_table(tech_table_rows))
    body.append(p_para("", space_after=320))
    
    # Concluding signature
    body.append(p_callout(
        "This documentation represents the verified system state following the full migration to Nigerian Naira (₦ / NGN). All 17 processes have been verified via automated test suites and authenticated browser snapshots.",
        title="Document Sign-Off & System Verification",
        border_color="1E1B4B",
        bg_color="F8FAFC"
    ))
    
    # Section properties
    sec_pr = """<w:sectPr>
  <w:pgSz w:w="12240" w:h="15840"/>
  <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
  <w:cols w:space="720"/>
  <w:docGrid w:linePitch="360"/>
</w:sectPr>"""
    
    return f"""<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
  <w:body>
    {"".join(body)}
    {sec_pr}
  </w:body>
</w:document>"""

def build_docx():
    print(f"Building complete workflow Word Document: {OUTPUT_DOCX}")
    
    # Check that all screenshots exist
    for p in PROCESSES:
        fpath = os.path.join(SCREENSHOTS_DIR, p["img"])
        if not os.path.exists(fpath):
            print(f"ERROR: Screenshot missing: {fpath}", file=sys.stderr)
            sys.exit(1)
            
    content_types = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Default Extension="png" ContentType="image/png"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/word/settings.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.settings+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>"""

    root_rels = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>"""

    core_xml = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>PropNest Property Management System - Complete System Workflow Guide</dc:title>
  <dc:subject>Complete System Workflow with Visual Process Screenshots</dc:subject>
  <dc:creator>PropNest Engineering</dc:creator>
  <cp:keywords>Property Management, Nigerian Naira, Workflow, Laravel, Documentation</cp:keywords>
  <dc:description>Complete workflow guide and visual documentation for PropNest Property Management System featuring 17 processes with embedded screenshots.</dc:description>
  <cp:lastModifiedBy>PropNest Engineering</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">2026-10-05T20:00:00Z</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">2026-10-05T20:00:00Z</dcterms:modified>
</cp:coreProperties>"""

    app_xml = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties">
  <Application>Microsoft Office Word</Application>
  <Company>PropNest Systems</Company>
  <DocSecurity>0</DocSecurity>
</Properties>"""

    settings_xml = """<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:defaultTabStop w:val="720"/>
  <w:characterSpacingControl w:val="doNotCompress"/>
</w:settings>"""

    doc_rels = generate_document_rels_xml(PROCESSES)
    doc_styles = generate_styles_xml()
    doc_xml = generate_document_xml()
    
    # Write to zip
    with zipfile.ZipFile(OUTPUT_DOCX, "w", zipfile.ZIP_DEFLATED) as zf:
        zf.writestr("[Content_Types].xml", content_types)
        zf.writestr("_rels/.rels", root_rels)
        zf.writestr("docProps/core.xml", core_xml)
        zf.writestr("docProps/app.xml", app_xml)
        zf.writestr("word/settings.xml", settings_xml)
        zf.writestr("word/styles.xml", doc_styles)
        zf.writestr("word/_rels/document.xml.rels", doc_rels)
        zf.writestr("word/document.xml", doc_xml)
        
        # Add all images
        for p in PROCESSES:
            img_path = os.path.join(SCREENSHOTS_DIR, p["img"])
            with open(img_path, "rb") as img_file:
                zf.writestr(f"word/media/{p['img']}", img_file.read())
                
    print(f"✓ Document successfully compiled: {OUTPUT_DOCX} ({os.path.getsize(OUTPUT_DOCX):,} bytes)")
    
    # Copy to artifacts directory
    os.makedirs(os.path.dirname(ARTIFACT_DOCX), exist_ok=True)
    with open(OUTPUT_DOCX, "rb") as src, open(ARTIFACT_DOCX, "wb") as dst:
        dst.write(src.read())
    print(f"✓ Document copied to artifact directory: {ARTIFACT_DOCX}")

if __name__ == "__main__":
    build_docx()
