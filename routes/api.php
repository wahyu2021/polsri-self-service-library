<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Vercel Cron Trigger
Route::get('/run-scheduler', function (Request $request) {
    // Simple security check using CRON_SECRET env var
    if ($request->query('token') !== config('app.cron_secret', 'my-secret-token')) {
        abort(401, 'Unauthorized');
    }

    $exitCode = Artisan::call('schedule:run');
    
    return response()->json([
        'message' => 'Scheduler executed',
        'exit_code' => $exitCode,
        'output' => Artisan::output()
    ]);
});
