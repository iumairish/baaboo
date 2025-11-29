<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\TicketSubmissionController;
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

// User Routes

Route::get('/', [TicketSubmissionController::class, 'create'])
    ->name('tickets.create');

Route::post('/tickets', [TicketSubmissionController::class, 'store'])
    ->name('tickets.store');

// Admin Routes

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('login');
        
        Route::post('/login', [AuthController::class, 'login'])
            ->name('login.submit');
    });

    // Authenticated admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        // Ticket management
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [TicketController::class, 'index'])
                ->name('index');
            
            Route::get('/{id}', [TicketController::class, 'show'])
                ->name('show');
            
            Route::post('/{id}/note', [TicketController::class, 'addNote'])
                ->name('add-note');
        });
    });
});