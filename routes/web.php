<?php

use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\Admin\EmergencyTypeController;
use App\Http\Controllers\Web\Admin\InviteController;
use App\Http\Controllers\Web\Admin\PermissionController;
use App\Http\Controllers\Web\Admin\RoleController;
use App\Http\Controllers\Web\Auth\RegisterController;
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

Route::prefix('admin')->middleware(['role:admin', 'auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admins.index');
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::post('roles/{role}/permissions', [RoleController::class, 'storePermissions'])->name('roles.permissions.store');
    Route::resource('emergency-types', EmergencyTypeController::class)->except(['create', 'edit']);

    // Invites
    Route::get('/invites', [InviteController::class, 'index'])->name('invites.index');
    Route::post('/invites', [InviteController::class, 'store'])->name('invites.store');
    Route::get('/invites/accept/{invite:code}', [InviteController::class, 'accept'])->name('invites.accept');
    Route::post('/invites/{invite:code}', [InviteController::class, 'process'])->name('invites.register');
    Route::post('/invites/{invite:code}/resend', [InviteController::class, 'resend'])->name('invites.resend');
    Route::delete('/invites/{invite:code}', [InviteController::class, 'destroy'])->name('invites.destroy');
});

Route::get('test', function () {
    return new SendInvite(Invite::factory()->create());
});
