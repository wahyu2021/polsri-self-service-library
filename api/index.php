<?php

/*
|--------------------------------------------------------------------------
| Vercel Entry Point
|--------------------------------------------------------------------------
|
| This file is the entry point for Vercel. It sets up the ephemeral
| /tmp directory for storage (logs, cache, views) since Vercel's
| file system is read-only.
|
*/

// 1. Define the storage path to /tmp (Ephemeral)
$storagePath = '/tmp/storage';

// 2. Create the directory structure if it doesn't exist
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    mkdir($storagePath . '/framework/views', 0777, true);
    mkdir($storagePath . '/framework/cache', 0777, true);
    mkdir($storagePath . '/framework/sessions', 0777, true);
    mkdir($storagePath . '/logs', 0777, true);
}

// 3. Inject the custom storage path into the application
// We hook into the bootstrap process by setting an environment variable
// or we can modify the application instance after it's loaded.
// However, the easiest way for Vercel is to let Laravel boot, then set path?
// No, paths are set early.

// Correct approach for Vercel + Laravel:
// We require the autoloader first.
require __DIR__ . '/../vendor/autoload.php';

// Create The Application
$app = require __DIR__ . '/../bootstrap/app.php';

// Set the storage path to /tmp
$app->useStoragePath($storagePath);

// Run The Application
$app->handleRequest(Illuminate\Http\Request::capture());
