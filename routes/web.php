<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\CoordinatorController;


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
    return view('home');
});

/*Login Routes*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->group(function() {
    Route::get('/login', [AuthController::class, 'adminLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'adminAuthenticate'])->name('admin.authenticate');
});

Route::prefix('coordinator')->group(function() {
    Route::get('/login', [AuthController::class, 'coordinatorLogin'])->name('coordinator.login');
    Route::post('/login', [AuthController::class, 'coordinatorAuthenticate'])->name('coordinator.authenticate');
});

Route::prefix('intern')->group(function() {
    Route::get('/login', [AuthController::class, 'internLogin'])->name('intern.login');
    Route::post('/login', [AuthController::class, 'internAuthenticate'])->name('intern.authenticate');
});

Route::prefix('hte')->group(function() {
    Route::get('/login', [AuthController::class, 'hteLogin'])->name('hte.login');
    Route::post('/login', [AuthController::class, 'hteAuthenticate'])->name('hte.authenticate');
});

// Protected admin routes
Route::middleware(['auth:web', 'admin'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('/coordinators', [AdminController::class, 'showCoordinators'])->name('admin.coordinators');

    Route::get('/coordinators/create', [AdminController::class, 'newCoordinator'])->name('admin.new_c');
    Route::post('/coordinators', [AdminController::class, 'registerCoordinator'])->name('admin.register_c');

    Route::get('/coordinators/{coordinator}/edit', [AdminController::class, 'editCoordinator'])->name('coordinators.edit');
    Route::delete('/coordinators/{coordinator}', [AdminController::class, 'destroyCoordinator'])->name('coordinators.destroy');

    Route::get('/departments', [AdminController::class, 'departments'])->name('admin.departments');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])->name('admin.new_d');
    Route::delete('/departments/{id}', [AdminController::class, 'deleteDepartment'])->name('admin.delete_D');
    
    Route::get('/skills', [AdminController::class, 'skills'])->name('admin.skills');
    Route::post('/skills', [AdminController::class, 'storeSkill'])->name('admin.new_skill');
    Route::put('/skills/{id}', [AdminController::class, 'updateSkill'])->name('admin.update_skill');
    Route::delete('/skills/{id}', [AdminController::class, 'deleteSkill'])->name('admin.delete_skill');
});

// Protected coordinator routes
Route::middleware(['auth:web', 'coordinator'])->prefix('coordinator')->group(function() {
    Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('coordinator.dashboard');
    
    Route::get('/interns', [CoordinatorController::class, 'showInterns'])->name('coordinator.interns');
    Route::get('/interns/create', [CoordinatorController::class, 'newIntern'])->name('coordinator.new_i');
    Route::post('/interns', [CoordinatorController::class, 'registerIntern'])->name('coordinator.register_i');

    Route::get('/htes', [CoordinatorController::class, 'htes'])->name('coordinator.htes');
    Route::get('/htes/create', [CoordinatorController::class, 'newHTE'])->name('coordinator.new_h');
    Route::post('/htes', [CoordinatorController::class, 'registerHTE'])->name('coordinator.register_h');

    Route::get('/htes/{id}', [CoordinatorController::class, 'showHTE'])->name('coordinator.hte.show');

    // For future implementation
    Route::get('/htes/{id}/edit', [CoordinatorController::class, 'editHTE'])->name('coordinator.hte.edit');
    Route::delete('/htes/{id}', [CoordinatorController::class, 'destroyHTE'])->name('coordinator.hte.destroy');

    Route::get('/deploy', [CoordinatorController::class, 'deploy'])->name('coordinator.deploy');

});

// Protected intern routes
Route::middleware(['auth:web', 'intern'])->prefix('intern')->group(function() {
    Route::get('/dashboard', [InternController::class, 'dashboard'])->name('intern.dashboard');

    Route::get('/skills', [InternController::class, 'selectSkills'])->name('intern.skills.select');
    Route::post('/skills/store', [InternController::class, 'saveSkills'])->name('intern.skills.store');

    Route::get('/docs', [InternController::class, 'documents'])->name('intern.docs');
    Route::post('/docs/upload', [InternController::class, 'uploadDocument'])->name('intern.docs.upload');
    Route::delete('/docs/delete', [InternController::class, 'deleteDocument'])->name('intern.docs.delete');
    Route::post('/update-status', [InternController::class, 'updateStatus'])->name('intern.update-status');
    Route::get('/check-documents', [InternController::class, 'checkDocumentsComplete'])
        ->name('intern.check-documents');
    Route::get('/profile', [InternController::class, 'profile'])->name('intern.profile');
    Route::put('/profile', [InternController::class, 'updateProfile'])->name('intern.profile.update');
    Route::post('/profile/picture', [InternController::class, 'updateProfilePicture'])->name('intern.profile.picture');
    Route::put('/profile/skills', [InternController::class, 'updateSkills'])->name('intern.skills.update');
   
});

// Protected HTE routes
Route::middleware(['auth:web', 'hte'])->prefix('hte')->group(function() {
    Route::get('/dashboard', [HteController::class, 'dashboard'])->name('hte.dashboard');
   
});

// HTE First Login
Route::middleware(['auth', 'hte'])->group(function() {
    Route::get('/hte/first-login/details', [HTEController::class, 'showDetailsForm'])
         ->name('hte.first-login.details');
         
    Route::put('/hte/first-login/confirm', [HTEController::class, 'confirmDetails'])
         ->name('hte.confirm-details');
         
    Route::get('/hte/first-login/skills', [HTEController::class, 'showSkillsForm'])
         ->name('hte.first-login.skills');

    Route::get('/hte/skills/select', [HTEController::class, 'showSkillsForm'])
         ->name('hte.first-login.skills');
         
    Route::post('/hte/skills/save', [HTEController::class, 'saveSkills'])
         ->name('hte.save-skills');
});

/* System */
// For coordinators
// Unified password setup routes
Route::get('/setup-password/{token}/{role}', [AuthController::class, 'showSetupForm'])
    ->where('role', 'coordinator|intern|hte') // Only accept these values
    ->name('password.setup');

Route::post('/setup-password/{token}/{role}', [AuthController::class, 'processSetup'])
    ->where('role', 'coordinator|intern|hte');


    Route::get('/test-mail', function() {
    return view('emails.hte-setup', [
        'contactName' => 'Test Name',
        'organizationName' => 'Test Org',
        'contactEmail' => 'test@example.com',
        'tempPassword' => 'temp123',
        'setupLink' => '#',
        'hasMoa' => true
    ]);
});





