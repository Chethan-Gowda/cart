<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminLoginController;

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



/* Admin */
Route::get('/admin/home', [AdminHomeController::class, 'index'])->name('admin_home')->middleware('admin:admin');
Route::get('/admin/login', [AdminLoginController::class, 'login'])->name('admin_login');
Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin_logout');
Route::post('/admin/login', [AdminLoginController::class, 'loginSubmit'])->name('admin_login_submit');
Route::get('/admin/forget-password', [AdminLoginController::class, 'forgetPassword'])->name('admin_forget_password');
Route::post('/admin/forget-password', [AdminLoginController::class, 'forgetPasswordSubmit'])->name('admin_forget_passowrd_submit');

Route::get('/admin/reset-password/{token}/{email}', [AdminLoginController::class, 'resetPassword']);
Route::post('/admin/reset-passoword', [AdminLoginController::class, 'resetPasswordSubmit'])->name('admin_reset_password_submit');