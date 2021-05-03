<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth:sanctum')->get('/', [AuthController::class, 'dashboard']);
Route::get('/login', [AuthController::class, 'loginForm'])->middleware('guest')->name('login');
Route::get('/registration', [AuthController::class, 'registrationForm'])->middleware('guest');


Route::get('/forgot-password',function (){ return view('auth.forgot-password');})->middleware('guest')->name('password.request');

Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password',[AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout'])->name('logout');