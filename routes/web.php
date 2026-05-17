<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\CompanySwitchController;

// ========== ROTA DE LOGIN ==========
// O Laravel já cria automaticamente as rotas de login
// Mas se precisar da raiz, redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== ROTA DE LOGOUT ==========
// ESTA É A LINHA QUE ESTAVA FALTANDO!
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ========== ROTAS AUTENTICADAS (protegidas) ==========
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ========== ROTAS DE CHAMADOS (TICKETS) ==========
    Route::resource('tickets', TicketController::class);
    
    // Rotas adicionais para chamados
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comments');
    
    // ========== ROTAS PARA ADMINISTRADORES ==========
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        
        // Gerenciamento de Empresas
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/toggle', [CompanyController::class, 'toggleActive'])->name('companies.toggle');
        
        // Gerenciamento de Setores
        Route::resource('sectors', SectorController::class);
        Route::get('sectors/by-company/{company}', [SectorController::class, 'getByCompany'])->name('sectors.byCompany');
        
        // Gerenciamento de Usuários
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        
        // Gerenciamento de Convites
        Route::resource('invites', InviteController::class);
        Route::post('invites/{invite}/resend', [InviteController::class, 'resend'])->name('invites.resend');
        Route::patch('invites/{invite}/cancel', [InviteController::class, 'cancel'])->name('invites.cancel');
    });
    
    // ========== SELETOR DE EMPRESA (para admins) ==========
    Route::get('/switch-company/{company}', [CompanySwitchController::class, 'switch'])->name('switch.company');
    Route::get('/switch-company-reset', [CompanySwitchController::class, 'reset'])->name('switch.company.reset');
});