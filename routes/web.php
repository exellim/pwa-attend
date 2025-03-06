<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class,'index'])->name('home.index');

    Route::get('/task', [TaskController::class,'index'])->name('task.index');

    Route::get('/attend/clock-in', [AttendanceController::class,'clockIn'])->name('attend.clockIn');
    Route::post('/attend/clocked-in', [AttendanceController::class,'submitIn'])->name('attend.submitIn');

    Route::get('/attend/clock-out', [AttendanceController::class,'clockOut'])->name('attend.clockOut');
    Route::post('/attend/clocked-out', [AttendanceController::class,'submitOut'])->name('attend.submitOut');

});

require __DIR__.'/auth.php';
