<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\DataOtbController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OtbPersiteNToNController;

// Route default untuk mengarahkan ke home
Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

// HomeController Routes
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/maps', [App\Http\Controllers\HomeController::class, 'show'])->name('maps');

// CustomerController Routes
Route::resource('customer', CustomerController::class);
Route::get('/customer/createWithMemberData/{id}', [CustomerController::class, 'show'])->name('customer.createWithMemberData');
Route::post('/customer/status/{id}', [CustomerController::class, 'changeStatus'])->name('customer.changeStatus');
Route::get('/customers/map', [CustomerController::class, 'showMap'])->name('customers.map');
Route::get('/customers/member', [CustomerController::class, 'member'])->name('customers.member');

// DataOtbController Routes
Route::resource('data_otb', DataOtbController::class);
Route::get('/data-otb/site', [DataOtbController::class, 'site'])->name('data_otb.site');
Route::get('/data-otb/get-data', [DataOtbController::class, 'getData'])->name('data_otb.get_data');
Route::get('/sata-otb/map', [DataOtbController::class, 'showMap'])->name('data_otb.map');
Route::get('/sata-otb/allsite', [DataOtbController::class, 'showAllMap'])->name('data_otb.allsite');

// AssetController Routes
Route::resource('asset', AssetController::class);
Route::post('asset/store', [AssetController::class, 'store'])->name('asset.store');
Route::get('/get-image-by-site', [AssetController::class, 'getImageBySite']);

// FrontendController Routes
Route::resource('/home', FrontendController::class);
Route::get('/', [FrontendController::class, 'index'])->name('home.index');
Route::post('/login', [FrontendController::class, 'login'])->name('login');
Route::post('/register', [FrontendController::class, 'store'])->name('register');
Route::post('/logout', [FrontendController::class, 'logout'])->name('logout');
Route::post('/cek-tagihan', [FrontendController::class, 'cekTagihan'])->name('cekTagihan');
// PaketController Routes
Route::resource('paket', PaketController::class);

// OtbPersiteNToNController Routes
Route::resource('otb_persite_n_t_on', OtbPersiteNToNController::class);


//invoices
Route::resource('invoices', InvoiceController::class);
Route::get('/invoice/{invoice_number}', [InvoiceController::class, 'showInvoice'])->name('invoice.show'); // Route::get('/payment', function () {
Route::get('/invoice/html/{invoice_number}', [InvoiceController::class, 'getInvoiceHtml'])->name('invoice.html');


//     return view('payment');
// })->name('payment.form');
