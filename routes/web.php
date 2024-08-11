<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Konfigurasi\AksesRoleController;
use App\Http\Controllers\Konfigurasi\AksesUserController;
use App\Http\Controllers\Konfigurasi\MenuController;
use App\Http\Controllers\Konfigurasi\PermissionController;
use App\Http\Controllers\Konfigurasi\RoleController;
use App\Http\Controllers\Konfigurasi\UserController;
use App\Http\Controllers\MasterData\TagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'konfigurasi', 'as' => 'konfigurasi.'], function() {
        Route::put('menu/sort', [MenuController::class, 'sort'])->name('menu.sort');
        Route::resource('menu', MenuController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::get('akses-role/{role}/role',[AksesRoleController::class, 'getPermissionsByRole']);
        Route::resource('akses-role', AksesRoleController::class)->except(['create', 'store', 'delete'])->parameters(['akses-role' => 'role']);
        Route::get('akses-user/{user}/user',[AksesUserController::class, 'getPermissionsByUser']);
        Route::resource('akses-user', AksesUserController::class)->except(['create', 'store', 'delete'])->parameters(['akses-user' => 'user']);
        Route::resource('users', UserController::class);
    });

    Route::group(['prefix' => 'master-data','as' => 'master-data.'], function() {
        Route::resource('tags', TagController::class);

    });

    Route::put('articles/{article}/approve', [ArticleController::class, 'storeApprove'])->name('articles.storeApprove')->can('approve articles');
    Route::get('articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve')->can('approve articles');
    Route::resource('articles', ArticleController::class);
    Route::get('notifications/{notification}', NotificationController::class )->name('notifications');
});

require __DIR__.'/auth.php';
