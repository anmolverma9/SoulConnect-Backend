<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => config('app.name', 'Soul Connect'),
        'version' => '1.0.0',
        'status' => 'active',
        'documentation' => '/api/v1',
        'admin_portal' => '/admin',
    ]);
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});
