<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleRegistrationController;
use App\Http\Controllers\AdditionalEquipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Item Visualizer (Legacy)
Route::get('/items', function () {
    return view('item-visualizer');
})->name('items.index');

// Vehicle Visualizer
Route::get('/vehicles', function () {
    return view('vehicle-visualizer');
});

// Buyer Visualizer
Route::get('/buyers', function () {
    return view('buyer-visualizer');
});

// Seller Visualizer
Route::get('/sellers', function () {
    return view('seller-visualizer');
});

// Employee Visualizer
Route::get('/employees', function () {
    return view('employee-visualizer');
});

// Purchase Contract Visualizer
Route::get('/purchase-contracts', function () {
    return view('purchase-contract-visualizer');
});

// Reference Data Visualizer
Route::get('/reference-data', function () {
    return view('reference-data');
});

// Vehicle Registration Visualizer
Route::get('/vehicle-registrations', function () {
    return view('vehicle-registration-visualizer');
});

// Additional Equipment Visualizer
Route::get('/additional-equipment', function () {
    return view('additional-equipment-visualizer');
});
