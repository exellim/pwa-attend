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

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class,'index'])->name('home.index');

    Route::get('/task', [TaskController::class,'index'])->name('task.index');


    // Clock In & Out

    Route::get('/attend/clock-in', [AttendanceController::class,'clockIn'])->name('attend.clockIn');
    Route::post('/attend/clocked-in', [AttendanceController::class,'submitIn'])->name('attend.submitIn');
    Route::get('/attend/clock-out', [AttendanceController::class,'clockOut'])->name('attend.clockOut');
    Route::post('/attend/clocked-out', [AttendanceController::class,'submitOut'])->name('attend.submitOut');

    // Overtime In & Out
    Route::get('/over/clock-in', [AttendanceController::class,'ovtIn'])->name('attend.ovtclockIn');
    Route::post('/over/clocked-in', [AttendanceController::class,'submitovtIn'])->name('attend.ovtsubmitIn');
    Route::get('/over/clock-out', [AttendanceController::class,'ovtOut'])->name('attend.covtlockOut');
    Route::post('/over/clocked-out', [AttendanceController::class,'submitovtOut'])->name('attend.ovtsubmitOut');

    Route::post('/task/str', [TaskController::class,'store'])->name('task.store');

});

require __DIR__.'/auth.php';
