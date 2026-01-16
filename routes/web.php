<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LoyaltyController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Lead Management
    Route::resource('leads', LeadController::class);
    
    // Client Management
    Route::resource('clients', ClientController::class);
    
    // Deal/Pipeline Management
    Route::resource('deals', DealController::class);
    
    // Quote Management
    Route::resource('quotes', QuoteController::class);
    Route::post('quotes/{quote}/pdf', [QuoteController::class, 'generatePdf'])->name('quotes.pdf');
    
    // Payment Management
    Route::resource('payments', PaymentController::class);
    
    // Loyalty Program
    Route::get('loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('loyalty/{client}/award', [LoyaltyController::class, 'awardPoints'])->name('loyalty.award');
    Route::post('loyalty/{client}/redeem', [LoyaltyController::class, 'redeemPoints'])->name('loyalty.redeem');
});

require __DIR__.'/auth.php';
