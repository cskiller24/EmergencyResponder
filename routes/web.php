<?php

use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\EmergencyTypeController;
use App\Http\Controllers\Web\Admin\InviteController;
use App\Http\Controllers\Web\Admin\PermissionController;
use App\Http\Controllers\Web\Admin\RoleController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Moderator\SubmissionController;
use App\Http\Controllers\Web\UserController;
use App\Mail\SendInvite;
use App\Models\Invite;
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

Route::get('/register', [RegisterController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register')->middleware('guest');

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    Route::post('roles/{role}/permissions', [RoleController::class, 'storePermissions'])->name('roles.permissions.store');
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::resource('emergency-types', EmergencyTypeController::class)->except(['create', 'edit']);

    // Invites
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites', [InviteController::class, 'store'])->name('invites.store');
    Route::post('/invites/{invite:code}/resend', [InviteController::class, 'resend'])->name('invites.resend');
    Route::delete('/invites/{invite:code}', [InviteController::class, 'destroy'])->name('invites.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});

// Moderator
Route::group(['prefix' => 'moderator', 'middleware' => ['auth', 'role:moderator'], 'as' => 'moderator.'], function () {
    Route::get('/', function () {
        return view('moderator.index');
    })->name('index');

    Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
});

// User
Route::group(['prefix' => 'user', 'middleware' => ['role:user', 'auth'], 'as' => 'user.'], function() {
    Route::get('/', function () {

    });
});

Route::get('/invites/accept/{invite:code}', [InviteController::class, 'accept'])->name('invites.accept');
Route::post('/invites/{invite:code}', [InviteController::class, 'process'])->name('invites.register');

Route::get('/settings', [UserController::class, 'edit'])->middleware('auth')->name('settings.show');
Route::put('/settings', [UserController::class, 'update'])->middleware('auth')->name('settings.update');

Route::get('test', function () {
    return new SendInvite(Invite::factory()->create());
});
