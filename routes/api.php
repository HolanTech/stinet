<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/pakets', [PaketController::class, 'api']);
Route::post('/cek-tagihan', [InvoiceController::class, 'cekTagihan']);
Route::post('/process-payment', [InvoiceController::class, 'processPayment']);
Route::post('/midtrans-notification', [InvoiceController::class, 'midtransNotification']);
Route::get('/check-midtrans-config', [InvoiceController::class, 'checkMidtransConfig']);
