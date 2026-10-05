# PropNest - Full-Featured Property Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vercel Ready](https://img.shields.io/badge/Vercel-Deployment_Ready-000000?style=for-the-badge&logo=vercel&logoColor=white)](https://vercel.com)
[![Tests Passing](https://img.shields.io/badge/Tests-21_Passed_%2F_75_Assertions-brightgreen?style=for-the-badge)](tests/Feature)

**PropNest** is a property management platform built on Laravel 12, Tailwind CSS v4, and SQLite. Engineered with Role-Based Access Control (RBAC), it provides a responsive interface balancing resident self-service with landlord portfolio administration.

---

## The Five Key Design Components

1. **Tenant Authentication and Role Management (RBAC)**
   - Secure login & registration with distinct `tenant` and `landlord` roles.
   - Route-level middleware isolation (`EnsureLandlord`, `EnsureTenant`).
   - Integrated 1-click instant demo role-switcher for fast evaluation.

2. **User-Friendly and Responsive Interface**
   - Built with Tailwind CSS v4 and Instrument Sans typography.
   - Dynamic navigation bar with live unread notification badge counter.
   - Fully responsive layouts for desktop, tablet, and mobile devices.

3. **Property Catalogue and Management**
   - Multi-criteria real-time filtering: search keywords, property types, price slider, bedrooms/bathrooms, and facility tags.
   - Rich unit showcases with image galleries, specs, and amenities badges.
   - Landlord unit publishing, editing, and resident tenancy assignment.

4. **Rent and Payment Management**
   - Multi-channel payment escrow: Zenith International Bank Wire transfer & Cryptocurrency payment channels (Tether USDT TRC-20, Bitcoin BTC, Ethereum ERC-20).
   - Tenant payment receipt & transaction hash proof upload.
   - Verifiable, printable digital payment receipts with audit serial numbers.
   - Landlord payment audit ledger with 1-click verification/rejection.
   - Landlord direct invoice issuance (Rent, Electricity, Maintenance, Water).

5. **Maintenance and Notification Management**
   - Tenant defect reporting with category classification, urgency levels, and photo attachments.
   - 3-stage visual progress timeline tracker (Submitted → Technician Dispatched / In Progress → Resolved).
   - Landlord centralized maintenance queue with contractor status controls.
   - Portfolio-wide broadcast announcements and personalized system notifications.

---

## Demo Accounts & Instant Login

| Role | Name | Email | Password |
| :--- | :--- | :--- | :--- |
| **Landlord / Administrator** | Alexander Vance | `landlord@property.com` | `password` |
| **Tenant (Primary)** | Michael Chen | `tenant@property.com` | `password` |
| **Tenant** | Sarah Jenkins | `sarah@property.com` | `password` |
| **Tenant** | David Okonjo | `david@property.com` | `password` |

*Note: You can also use the 1-click demo switcher in the top banner to swap between Landlord and Tenant immediately.*

---

## Vercel Deployment Guide

This project is configured for serverless deployment on **[Vercel](https://vercel.com)** out of the box via `vercel.json` and the `api/index.php` serverless entrypoint.

### Steps to Deploy:

1. **Import to Vercel**:
   - Go to [vercel.com/new](https://vercel.com/new)
   - Connect your GitHub account and import `boteny02/propertysystem`
   - Select **Other** as the Framework Preset (root directory `./`)

2. **Environment Variables**:
   In your Vercel Project Settings under **Environment Variables**, you can optionally configure:
   ```env
   APP_NAME="PropNest"
   APP_ENV="production"
   APP_KEY="base64:RWPMcFxwOHGGU4RxX6Z7gQBRg1Tpri6y1Q9INBE0WSs="
   APP_DEBUG="false"
   APP_URL="https://your-vercel-domain.vercel.app"
   SESSION_DRIVER="cookie"
   DB_CONNECTION="sqlite"
   DB_DATABASE="/tmp/database.sqlite"
   ```
   *(A pre-configured fallback `APP_KEY` and SQLite setup is already embedded in `vercel.json` for immediate plug-and-play).*

3. **Deploy**:
   - Click **Deploy**. Vercel will process the serverless PHP runtime and serve static compiled Vite assets automatically.

---

## Local Development Setup

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+ & NPM
- Laravel Herd, Valet, or PHP built-in server

### Installation
```bash
# 1. Clone repository
git clone https://github.com/boteny02/propertysystem.git
cd propertysystem

# 2. Install dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Database setup & seeding
touch database/database.sqlite
php artisan migrate --seed

# 5. Build assets & run server
npm run build
php artisan serve
```

Visit `http://localhost:8000` or `http://propertysystem.test` if using Laravel Herd.

---

## Running Automated Tests

```bash
php artisan test
```

All 21 feature tests and 75 assertions pass across authentication, properties, payments, and maintenance suites.

---

## Documentation & Visual Artifacts

- **Workflow Guide (Markdown)**: [`property_management_workflow.md`](property_management_workflow.md)
- **Workflow Guide (Word Document)**: [`property_management_workflow.docx`](property_management_workflow.docx) (Includes all 17 high-resolution process screenshots)
