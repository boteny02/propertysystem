<?php

/**
 * Entry point for Vercel Serverless Function
 */

// Ensure writable directories in /tmp for serverless storage
$storageDirs = [
    '/tmp/storage',
    '/tmp/storage/framework',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/views',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Copy pre-seeded SQLite database to writable /tmp directory if not already created
$tmpDb = '/tmp/database.sqlite';
if (!file_exists($tmpDb)) {
    $seededDb = __DIR__ . '/../database/database.sqlite';
    if (file_exists($seededDb) && filesize($seededDb) > 0) {
        @copy($seededDb, $tmpDb);
    } else {
        @touch($tmpDb);
    }
}

// Ensure VERCEL environment indicator is active
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// Forward execution to standard Laravel public/index.php
require __DIR__ . '/../public/index.php';
