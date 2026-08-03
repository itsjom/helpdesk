<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DisposalForm;
use App\Livewire\Admin\RecommendationForm;
use App\Livewire\Admin\Reports;
use App\Livewire\Admin\TicketTable;
use App\Livewire\Admin\UserManagement;
use App\Livewire\User\MyTickets;
use App\Livewire\User\TicketForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $admin = \App\Models\User::where('role', 'admin')->first();
    if ($admin) {
        \Illuminate\Support\Facades\Auth::login($admin);
        return redirect()->route('admin.dashboard');
    }
    abort(403, 'No admin user found in the system.');
});

Route::get('/bypass-user', function () {
    $user = \App\Models\User::where('role', 'user')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('user.tickets');
    }
    abort(403, 'No standard user found in the system.');
});

// Admin Routes
Route::middleware(['auth', 'role:admin', 'nocache'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/tickets', TicketTable::class)->name('admin.tickets');
    Route::get('/admin/tickets/create', \App\Livewire\User\TicketForm::class)->name('admin.tickets.create');
    Route::get('/admin/tickets/{ticketId}/recommendation', RecommendationForm::class)->name('admin.tickets.recommendation');
    Route::get('/admin/tickets/{ticketId}/disposal', DisposalForm::class)->name('admin.tickets.disposal');
    Route::get('/admin/users', UserManagement::class)->name('admin.users');
    Route::get('/admin/reports', Reports::class)->name('admin.reports');
    Route::get('/admin/reports/pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.pdf');
});

// User Routes
Route::middleware(['auth', 'role:user', 'nocache'])->group(function () {
    Route::get('/tickets', MyTickets::class)->name('user.tickets');
    Route::get('/tickets/create', TicketForm::class)->name('user.tickets.create');
});

Route::view('profile', 'profile')
    ->middleware(['auth', 'nocache'])
    ->name('profile');

