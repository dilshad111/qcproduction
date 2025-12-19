<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

    Route::get('/home', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    
    Route::get('masters', [App\Http\Controllers\MastersController::class, 'index'])->name('masters.index');
    Route::post('masters/ink', [App\Http\Controllers\MastersController::class, 'storeInk'])->name('masters.ink.store');
    Route::delete('masters/ink/{ink}', [App\Http\Controllers\MastersController::class, 'destroyInk'])->name('masters.ink.destroy');
    
    Route::post('masters/carton-type', [App\Http\Controllers\MastersController::class, 'storeCartonType'])->name('masters.carton-type.store');
    Route::delete('masters/carton-type/{cartonType}', [App\Http\Controllers\MastersController::class, 'destroyCartonType'])->name('masters.carton-type.destroy');
    
    Route::post('masters/machine-speed', [App\Http\Controllers\MastersController::class, 'updateMachineSpeed'])->name('masters.machine-speed.update');
    Route::post('masters/job-number-setup', [App\Http\Controllers\MastersController::class, 'storeJobNumberSetup'])->name('masters.job-number-setup.store');
    
    Route::post('masters/paper', [App\Http\Controllers\MastersController::class, 'storePaper'])->name('masters.paper.store');
    Route::delete('masters/paper/{paper}', [App\Http\Controllers\MastersController::class, 'destroyPaper'])->name('masters.paper.destroy');

    Route::post('masters/machine', [App\Http\Controllers\MastersController::class, 'storeMachine'])->name('masters.machine.store');
    Route::delete('masters/machine/{machine}', [App\Http\Controllers\MastersController::class, 'destroyMachine'])->name('masters.machine.destroy');

    Route::post('masters/staff', [App\Http\Controllers\MastersController::class, 'storeStaff'])->name('masters.staff.store');
    Route::delete('masters/staff/{staff}', [App\Http\Controllers\MastersController::class, 'destroyStaff'])->name('masters.staff.destroy');

    Route::resource('job-cards', App\Http\Controllers\JobCardController::class);
    Route::get('job-cards/{job_card}/print', [App\Http\Controllers\JobCardController::class, 'print'])->name('job-cards.print');
    Route::get('api/customer/{customer}/jobs', [App\Http\Controllers\JobCardController::class, 'getByCustomer'])->name('api.customer.jobs');
    Route::post('job-cards/generate-dieline', [App\Http\Controllers\JobCardController::class, 'generateDieLine'])->name('job-cards.generate-dieline');
    
    Route::resource('production', App\Http\Controllers\JobIssueController::class);
    
    Route::get('production/{jobIssue}/manage', [App\Http\Controllers\ProductionTrackingController::class, 'manage'])->name('production.manage');
    Route::post('production/{jobIssue}/reel', [App\Http\Controllers\ProductionTrackingController::class, 'storeReel'])->name('production.reel.store');
    Route::post('production/{jobIssue}/process', [App\Http\Controllers\ProductionTrackingController::class, 'updateProcess'])->name('production.process.update');
    Route::post('production/{jobIssue}/inventory', [App\Http\Controllers\ProductionTrackingController::class, 'storeInventoryLog'])->name('production.inventory.store');
    Route::post('production/{jobIssue}/dispatch', [App\Http\Controllers\ProductionTrackingController::class, 'storeDispatch'])->name('production.dispatch.store');

    Route::get('/company/setup', [App\Http\Controllers\CompanyController::class, 'edit'])->name('company.setup');
    Route::post('/company/setup', [App\Http\Controllers\CompanyController::class, 'update'])->name('company.update');

    // Corrugation Plant Module
    Route::get('corrugation', [App\Http\Controllers\CorrugationController::class, 'index'])->name('corrugation.index');
    Route::get('corrugation/report', [App\Http\Controllers\CorrugationController::class, 'report'])->name('corrugation.report');
    Route::get('corrugation/{jobIssue}', [App\Http\Controllers\CorrugationController::class, 'manage'])->name('corrugation.manage');
    Route::post('corrugation/{jobIssue}/start', [App\Http\Controllers\CorrugationController::class, 'startJob'])->name('corrugation.start');
    Route::post('corrugation/{log}/end', [App\Http\Controllers\CorrugationController::class, 'endJob'])->name('corrugation.end');
    Route::post('corrugation/{log}/downtime', [App\Http\Controllers\CorrugationController::class, 'storeDowntime'])->name('corrugation.downtime.store');
    Route::post('corrugation/{log}/wastage', [App\Http\Controllers\CorrugationController::class, 'logWastage'])->name('corrugation.wastage');

    // User Management
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('users/{user}/rights', [App\Http\Controllers\UserController::class, 'editRights'])->name('users.rights');
    Route::post('users/{user}/rights', [App\Http\Controllers\UserController::class, 'updateRights'])->name('users.rights.update');
    Route::post('theme/update', [App\Http\Controllers\UserController::class, 'updateTheme'])->name('theme.update');
    
    Route::get('profile', [App\Http\Controllers\UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('audit-logs', [App\Http\Controllers\AuditController::class, 'index'])->name('audits.index');
});
