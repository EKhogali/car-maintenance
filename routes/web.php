<?php

use Illuminate\Support\Facades\Route;
use App\Models\MaintenanceRecord;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-invoice/{id}', function ($id) {
    $record = MaintenanceRecord::with(['car.customer', 'mechanic', 'serviceTypes'])->findOrFail($id);
    return view('customer-invoice', compact('record'));
});
