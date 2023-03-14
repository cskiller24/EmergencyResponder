<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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

Route::prefix('admin')->middleware(['role:admin', 'auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admins.index');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->except(['create']);
});

// Route::get('test', function () {
//     return view('auth.password-update');
// });
