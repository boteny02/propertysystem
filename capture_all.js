import puppeteer from 'puppeteer-core';
import path from 'path';
import fs from 'fs';

const SCREENSHOTS_DIR = path.join(process.cwd(), 'screenshots');
const CHROME_PATH = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const BASE_URL = 'http://127.0.0.1:8000';

if (!fs.existsSync(SCREENSHOTS_DIR)) {
    fs.mkdirSync(SCREENSHOTS_DIR, { recursive: true });
}

// Clean temporary profile dir
const PROFILE_DIR = '/tmp/chrome_pptr_profile';
if (fs.existsSync(PROFILE_DIR)) {
    fs.rmSync(PROFILE_DIR, { recursive: true, force: true });
}

async function run() {
    console.log('Launching Chrome via puppeteer-core on port 8000...');
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            `--user-data-dir=${PROFILE_DIR}`
        ],
        defaultViewport: { width: 1280, height: 850 }
    });

    const page = await browser.newPage();

    async function snap(url, filename) {
        console.log(`Navigating to ${url}...`);
        await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 15000 });
        await new Promise(r => setTimeout(r, 1000)); // allow CSS and layout rendering
        const dest = path.join(SCREENSHOTS_DIR, filename);
        await page.screenshot({ path: dest, fullPage: false });
        console.log(`✓ Saved ${filename}`);
    }

    // 1. Public & Auth Pages
    console.log('\n--- 1. Public & Auth Pages ---');
    await snap(`${BASE_URL}/catalogue`, '01_property_catalogue.png');
    await snap(`${BASE_URL}/properties/1`, '02_property_details.png');
    await snap(`${BASE_URL}/login`, '03_login_page.png');
    await snap(`${BASE_URL}/register`, '04_register_page.png');

    // 2. Tenant Workflow
    console.log('\n--- 2. Tenant Workflow ---');
    await snap(`${BASE_URL}/demo-login/tenant`, '05_tenant_dashboard.png');
    await snap(`${BASE_URL}/payments`, '06_tenant_payment_center.png');
    await snap(`${BASE_URL}/payments/create`, '07_submit_payment_receipt.png');
    await snap(`${BASE_URL}/payments/1`, '08_printable_receipt_slip.png');
    await snap(`${BASE_URL}/maintenance/create`, '09_submit_maintenance.png');
    await snap(`${BASE_URL}/maintenance/1`, '10_maintenance_ticket_timeline.png');
    await snap(`${BASE_URL}/notifications`, '11_notifications_feed.png');

    // 3. Landlord Operations Workflow
    console.log('\n--- 3. Landlord Operations Workflow ---');
    await snap(`${BASE_URL}/demo-login/landlord`, '12_landlord_dashboard.png');
    await snap(`${BASE_URL}/payments`, '13_landlord_payment_ledger.png');
    await snap(`${BASE_URL}/maintenance`, '14_landlord_maintenance_queue.png');
    await snap(`${BASE_URL}/admin/properties/create`, '15_landlord_add_property.png');
    await snap(`${BASE_URL}/admin/bills/create`, '16_landlord_issue_bill.png');
    await snap(`${BASE_URL}/admin/announcements/create`, '17_landlord_post_announcement.png');

    await browser.close();
    console.log('\nAll 17 process screenshots captured successfully!');
}

run().catch(err => {
    console.error('Fatal error:', err);
    process.exit(1);
});
