<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'service' => 'CPV Suggestion Service',
        'version' => '1.0.0',
        'status' => 'operational'
    ]);
});
