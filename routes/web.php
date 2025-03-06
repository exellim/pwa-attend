<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class,'index'])->name('home.index');

Route::get('/task', [TaskController::class,'index'])->name('task.index');

Route::get('/attend/clock-in', [AttendanceController::class,'clockIn'])->name('attend.clockIn');
