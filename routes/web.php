<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Farmer\DashboardController;
use App\Http\Controllers\Farmer\FarmController;
use App\Http\Controllers\Farmer\LoanController;
use App\Http\Controllers\Farmer\InsuranceController;
use App\Http\Controllers\Farmer\TransactionController;
use App\Http\Controllers\Farmer\ReportController;
use App\Http\Controllers\Farmer\PlanController;
use App\Http\Controllers\Insurer\InsurerController;
use App\Http\Controllers\Insurer\InsurancePlanController;
use App\Http\Controllers\Insurer\PolicyController;
use App\Http\Controllers\Insurer\ClaimsController;
use App\Http\Controllers\Insurer\PaymentsController;
use App\Http\Controllers\Lender\DashboardController as LenderDashboardController;
use App\Http\Controllers\Lender\LoanController as LenderLoanController;
use App\Http\Controllers\Lender\TransactionController as LenderTransactionController;
use App\Http\Controllers\Lender\ReportController as LenderReportController;
use App\Http\Controllers\Lender\LenderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthenticationController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::resource('profile', ProfileController::class);
});

// Logout route (GET method for easier access from layouts)
Route::get('/logout', [LoginController::class, 'destroy'])->name('logout.get');

Route::prefix('farmer')->middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('farmer.dashboard');
    Route::resource('farms', FarmController::class)->names('farmer.farms');
    Route::resource('loans', LoanController::class)->names('farmer.loans');
    // Additional insurance routes (MUST come before resource route)
    Route::get('/insurance/plans', [InsuranceController::class, 'getPlans'])->name('farmer.insurance.plans');
    Route::post('/insurance/{insurance}/claim', [InsuranceController::class, 'submitClaim'])->name('farmer.insurance.claim');
    Route::patch('/insurance/{insurance}/cancel', [InsuranceController::class, 'cancelPolicy'])->name('farmer.insurance.cancel');

    Route::resource('insurance', InsuranceController::class)->names('farmer.insurance');

    Route::resource('transactions', TransactionController::class)->names('farmer.transactions');
    Route::resource('plans', PlanController::class)->names('farmer.plans');
    Route::get('/reports', [ReportController::class, 'index'])->name('farmer.reports');
});

Route::prefix('insurer')->middleware(['auth', 'role:insurer'])->group(function () {
    Route::get('/dashboard', [InsurerController::class, 'index'])->name('insurer.dashboard');
    Route::get('/profile', [InsurerController::class, 'profile'])->name('insurer.profile');
    Route::post('/profile', [InsurerController::class, 'updateProfile'])->name('insurer.profile.update');

    // Insurance Plans Management
    Route::resource('plans', InsurancePlanController::class)->names('insurer.plans');

    // Policies Management
    Route::resource('policies', PolicyController::class)->names('insurer.policies')->only(['index', 'show']);
    Route::patch('/policies/{policy}/status', [PolicyController::class, 'updateStatus'])->name('insurer.policies.status');

    // Claims Management
    Route::resource('claims', ClaimsController::class)->names('insurer.claims')->only(['index', 'show']);
    Route::patch('/claims/{claim}/status', [ClaimsController::class, 'updateStatus'])->name('insurer.claims.status');
    Route::post('/claims/{claim}/investigation-notes', [ClaimsController::class, 'addInvestigationNotes'])->name('insurer.claims.investigation-notes');

    // Payments Management
    Route::resource('payments', PaymentsController::class)->names('insurer.payments')->only(['index', 'show']);
    Route::patch('/payments/{payment}/status', [PaymentsController::class, 'updateStatus'])->name('insurer.payments.status');
    Route::get('/payments/export', [PaymentsController::class, 'export'])->name('insurer.payments.export');
});

Route::prefix('lender')->middleware(['auth', 'role:lender'])->group(function () {
    Route::get('/dashboard', [LenderDashboardController::class, 'index'])->name('lender.dashboard');
    Route::get('/profile', [LenderController::class, 'profile'])->name('lender.profile');
    Route::post('/profile', [LenderController::class, 'updateProfile'])->name('lender.profile.update');
    Route::post('/password', [LenderController::class, 'updatePassword'])->name('lender.password.update');
    Route::post('/settings', [LenderController::class, 'updateSettings'])->name('lender.settings.update');

    // Loan management
    Route::get('/loans', [LenderLoanController::class, 'index'])->name('lender.loans.index');
    Route::get('/loans/applications', [LenderLoanController::class, 'applications'])->name('lender.loans.applications');
    Route::get('/loans/active', [LenderLoanController::class, 'activeLoans'])->name('lender.loans.active');
    Route::put('/loans/{loan}/approve', [LenderLoanController::class, 'approve'])->name('lender.loans.approve');
    Route::put('/loans/{loan}/reject', [LenderLoanController::class, 'reject'])->name('lender.loans.reject');
    Route::put('/loans/{loan}/status', [LenderLoanController::class, 'updateStatus'])->name('lender.loans.status');
    Route::get('/loans/{loan}', [LenderLoanController::class, 'show'])->name('lender.loans.show');

    // Transaction management
    Route::get('/transactions', [LenderTransactionController::class, 'index'])->name('lender.transactions.index');
    Route::get('/transactions/{transaction}', [LenderTransactionController::class, 'show'])->name('lender.transactions.show');
    Route::get('/farms/{farm}/transactions', [LenderTransactionController::class, 'farmTransactions'])->name('lender.farms.transactions');
    Route::get('/farms/{farm}/transactions/create', [LenderTransactionController::class, 'create'])->name('lender.transactions.create');
    Route::post('/farms/{farm}/transactions', [LenderTransactionController::class, 'store'])->name('lender.transactions.store');
    Route::get('/transactions/{transaction}/edit', [LenderTransactionController::class, 'edit'])->name('lender.transactions.edit');
    Route::put('/transactions/{transaction}', [LenderTransactionController::class, 'update'])->name('lender.transactions.update');
    Route::delete('/transactions/{transaction}', [LenderTransactionController::class, 'destroy'])->name('lender.transactions.destroy');

    // Reports
    Route::get('/reports', [LenderReportController::class, 'index'])->name('lender.reports.index');
    Route::get('/reports/loan-performance', [LenderReportController::class, 'loanPerformance'])->name('lender.reports.loan-performance');
    Route::get('/reports/financial-summary', [LenderReportController::class, 'financialSummary'])->name('lender.reports.financial-summary');
    Route::post('/reports/export', [LenderReportController::class, 'export'])->name('lender.reports.export');

    // Documents
    Route::get('/documents', [LenderController::class, 'documents'])->name('lender.documents');
    Route::post('/documents', [LenderController::class, 'uploadDocument'])->name('lender.documents.upload');
    Route::delete('/documents/{document}', [LenderController::class, 'deleteDocument'])->name('lender.documents.delete');
});
