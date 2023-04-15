<?php

use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\EmergencyTypeController;
use App\Http\Controllers\Web\Admin\InviteController;
use App\Http\Controllers\Web\Admin\PermissionController;
use App\Http\Controllers\Web\Admin\RoleController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\SettingsController;
use App\Http\Controllers\Web\Moderator\ResponderController;
use App\Http\Controllers\Web\Moderator\SubmissionController as ModeratorSubmissionController;
use App\Http\Controllers\Web\Public\ResponderController as PublicResponderController;
use App\Http\Controllers\Web\Public\SubmissionController as PublicSubmissionController;
use App\Http\Controllers\Web\User\SubmissionController as UserSubmissionController;
use App\Mail\SubmissionDeny;
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

Route::redirect('/', 'login');

Route::get('/register', [RegisterController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register')->middleware('guest');

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('roles/{role}/permissions', [RoleController::class, 'storePermissions'])->name('roles.permissions.store');
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::resource('emergency-types', EmergencyTypeController::class)->except(['create', 'edit']);

    // Invites
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites', [InviteController::class, 'store'])->name('invites.store');
    Route::post('/invites/{invite:code}/resend', [InviteController::class, 'resend'])->name('invites.resend');
    Route::delete('/invites/{invite:code}', [InviteController::class, 'destroy'])->name('invites.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
});

// Moderator
Route::group(['prefix' => 'moderator', 'middleware' => ['auth', 'role:moderator'], 'as' => 'moderator.'], function () {
    Route::view('/', 'moderator.index')->name('index');

    Route::group(['prefix' => 'submissions', 'as' => 'submissions.'], function () {
        Route::get('/', [ModeratorSubmissionController::class, 'index'])->name('index');
        Route::get('/my-submissions', [ModeratorSubmissionController::class, 'indexAuth'])->name('index.auth');
        Route::get('/{submission}', [ModeratorSubmissionController::class, 'show'])->name('show');
        Route::patch('/{submission}/moderator', [ModeratorSubmissionController::class, 'addModerator'])->name('moderate');
        Route::patch('/{submission}/approve', [ModeratorSubmissionController::class, 'approveSubmission'])->name('approve');
        Route::patch('/{submission}/deny', [ModeratorSubmissionController::class, 'denySubmission'])->name('deny');
    });

    Route::resource('responders', ResponderController::class)->except(['store', 'destroy']);
});

// User
Route::group(['as' => 'public.'], function () {
    Route::get('/home', function () {
        return view('public.index');
    })->name('index');

    Route::get('/submissions', [PublicSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/create', [UserSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [UserSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [PublicSubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{submission}/edit', [UserSubmissionController::class, 'edit'])->name('submission.edit');
    Route::put('/submissions/{submission}', [UserSubmissionController::class, 'update'])->name('submissions.update');

    Route::get('/responders/', [PublicResponderController::class, 'index'])->name('responders.index');
    Route::get('/responders/{responder}', [PublicResponderController::class, 'show'])->name('responders.show');
});

Route::get('/invites/accept/{invite:code}', [InviteController::class, 'accept'])->name('invites.accept');
Route::post('/invites/{invite:code}', [InviteController::class, 'process'])->name('invites.register');

Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

Route::get('/settings/password', [SettingsController::class, 'editPassword'])->name('settings.password.edit');
Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
Route::get('test', function () {
    return new SubmissionDeny();
});
