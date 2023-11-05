<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportListController;

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

// Route::middleware('auth')->group(function () 
//  {
//      Route::get('/', [UserController::class, 'index']);
//  });




 Route::middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/start-work', [AttendanceController::class, 'startWork'])->name('attendance.startWork');
    Route::post('/end-work', [AttendanceController::class, 'endWork'])->name('attendance.endWork');
    Route::post('/start-break', [AttendanceController::class, 'startBreak'])->name('break.startBreak');
    Route::post('/end-break', [AttendanceController::class, 'endBreak'])->name('break.endBreak');
    Route::get('/report-list', [ReportListController::class, 'index'])->name('report.list');

    
});

