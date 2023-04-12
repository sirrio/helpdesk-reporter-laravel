<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Redirect::route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/contact', function () {
        return Inertia::render('Contact/Index');
    })->name('contact');

    Route::get('/dashboard', [AttendanceController::class, 'index'])
        ->name('dashboard');
    Route::post('/dashboard', [AttendanceController::class, 'store']);

    Route::get('/attendance/{attendance}', [AttendanceController::class, 'attendance'])
        ->name('attendance');
    Route::post('/attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::delete('/attendance', [AttendanceController::class, 'delete'])
        ->name('attendance.delete');

    Route::get('/csv/{semester}', [AttendanceController::class, 'csv'])
        ->name('csv');

    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->name('statistics');

    Route::get('/user', [UserController::class, 'index'])
        ->name('user');
    Route::delete('/user', [UserController::class, 'delete']);
    Route::post('/user/admin', [UserController::class, 'changeAdmin'])
        ->name('user.admin');
    Route::post('/user/mod', [UserController::class, 'changeMod'])
        ->name('user.mod');

    Route::get('/semester', [SemesterController::class, 'index'])
        ->name('semester');
    Route::post('/semester', [SemesterController::class, 'create']);
    Route::delete('/semester', [SemesterController::class, 'delete']);

    Route::get('/degree', [DegreeController::class, 'index'])
        ->name('degree');
    Route::post('/degree', [DegreeController::class, 'create']);
    Route::delete('/degree', [DegreeController::class, 'delete']);

    Route::get('/faculty', [FacultyController::class, 'index'])
        ->name('faculty');
    Route::post('/faculty', [FacultyController::class, 'create']);
    Route::delete('/faculty', [FacultyController::class, 'delete']);
});

require __DIR__.'/auth.php';
