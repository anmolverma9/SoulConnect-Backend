<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => config('app.name', 'DatingApp API'),
        'version' => '1.0.0',
        'status' => 'active',
        'documentation' => '/api/v1',
    ]);
});
