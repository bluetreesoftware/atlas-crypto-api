<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('consumers')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('sign-in', App\Http\Controllers\Consumer\Auth\SignInController::class)->name('consumers.auth.sign-in');
        Route::post('sign-up', App\Http\Controllers\Consumer\Auth\SignUpController::class)->name('consumers.auth.sign-up');
        Route::get('account', App\Http\Controllers\Consumer\Auth\AccountController::class)->name('consumers.auth.account')->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('wallets', App\Http\Controllers\Consumer\Wallet\WalletController::class)->names('consumers.wallets');
        Route::apiResource('wallets.transactions', App\Http\Controllers\Consumer\Wallet\Transaction\TransactionController::class)->names('consumers.wallets.transactions');

        Route::prefix('transactions')->group(function () {
            Route::post('w2w', App\Http\Controllers\Consumer\Transaction\W2W\W2WController::class)->name('consumers.transactions.w2w');
        });

        Route::post('trades/{id}/orders', [App\Http\Controllers\Consumer\Exchange\Trade\Order\OpenOrderController::class, 'open'])->name('consumers.trades.orders');
    });
});
