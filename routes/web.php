<?php

use App\Http\Controllers\AttendanceController;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/scan-absen', [QrCodeController::class, 'scan'])->name('scan.absen');
});
