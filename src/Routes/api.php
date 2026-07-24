<?php

use Illuminate\Support\Facades\Route;
use Zerp\Account\Http\Controllers\Api\DashboardApiController;
use Zerp\Account\Http\Controllers\Api\CustomerPaymentApiController;
use Zerp\Account\Http\Controllers\Api\VendorPaymentApiController;
use Zerp\Account\Http\Controllers\Api\ChartOfAccountApiController;
use Zerp\Account\Http\Controllers\Api\JournalEntryApiController;

Route::prefix('api')->middleware(['api.json'])->group(function () {
    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'accounting'], function () {
        // Dashboard
        Route::get('dashboard', [DashboardApiController::class, 'index']);

        // Customer Payments
        Route::apiResource('customer-payments', CustomerPaymentApiController::class)->only(['index', 'show', 'destroy']);

        // Vendor Payments
        Route::apiResource('vendor-payments', VendorPaymentApiController::class)->only(['index', 'show', 'destroy']);

        // Chart of Accounts
        Route::apiResource('chart-of-accounts', ChartOfAccountApiController::class);

        // Journal Entries
        Route::apiResource('journal-entries', JournalEntryApiController::class)->only(['index', 'show']);
    });
});
