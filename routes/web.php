<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\InviteRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\CompanySwitchController;

// ========== ROTAS DE AUTENTICAÇÃO ==========
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ROTA RAIZ ==========
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== ROTAS DE ACEITE DE CONVITE ==========
Route::get('/register/{token}', [InviteRegisterController::class, 'showRegisterForm'])->name('invite.register.form');
Route::post('/register/{token}', [InviteRegisterController::class, 'register'])->name('invite.register');

// ========== ROTAS AUTENTICADAS ==========
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comments');

    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/toggle', [CompanyController::class, 'toggleActive'])->name('companies.toggle');
        Route::resource('sectors', SectorController::class);
        Route::get('sectors/by-company/{company}', [SectorController::class, 'getByCompany'])->name('sectors.byCompany');
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::resource('invites', InviteController::class);
        Route::post('invites/{invite}/resend', [InviteController::class, 'resend'])->name('invites.resend');
        Route::patch('invites/{invite}/cancel', [InviteController::class, 'cancel'])->name('invites.cancel');
    });

    Route::get('/switch-company/{company}', [CompanySwitchController::class, 'switch'])->name('switch.company');
    Route::get('/switch-company-reset', [CompanySwitchController::class, 'reset'])->name('switch.company.reset');
});