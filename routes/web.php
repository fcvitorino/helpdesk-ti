<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\InviteRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SectorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\CompanySwitchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AuditController;

// ========== ROTAS DE RECUPERAÇÃO DE SENHA ==========
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// ========== ROTAS DE AUTENTICAÇÃO ==========
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ROTA RAIZ ==========
Route::get('/', function () {
    return redirect()->route('login');
});

// ========== ROTA PARA SELECIONAR EMPRESA ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/selecionar-empresa', function () {
        $companies = App\Models\Company::where('is_active', true)->orderBy('name')->get();
        return view('company.select', compact('companies'));
    })->name('company.select');
    
    Route::post('/selecionar-empresa', [CompanySwitchController::class, 'switch'])->name('company.select.store');
});

// ========== ROTAS DE ACEITE DE CONVITE ==========
Route::get('/register/{token}', [InviteRegisterController::class, 'showRegisterForm'])->name('invite.register.form');
Route::post('/register/{token}', [InviteRegisterController::class, 'register'])->name('invite.register');

// ========== ROTAS DE PERFIL (ALTERAR SENHA) ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ========== ROTAS DE NOTIFICAÇÕES ==========
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
});

// ========== ROTAS PROTEGIDAS ==========
Route::middleware(['auth', 'check.company'])->group(function () {

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

        // ========== AUDITORIA ==========
        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
        Route::get('/audit/export-pdf', [AuditController::class, 'exportPdf'])->name('audit.exportPdf');
    });

    Route::get('/switch-company/{company}', [CompanySwitchController::class, 'switch'])->name('switch.company');
    Route::get('/switch-company-reset', [CompanySwitchController::class, 'reset'])->name('switch.company.reset');
});