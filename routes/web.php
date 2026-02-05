<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\EmployeeShiftController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::put('/employees/{user}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{user}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::put('/shifts/{shift}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::get('/employee-shifts', [EmployeeShiftController::class, 'index'])->name('employee-shifts.index');
    Route::post('/employee-shifts', [EmployeeShiftController::class, 'store'])->name('employee-shifts.store');
    Route::put('/employee-shifts/{employeeShift}', [EmployeeShiftController::class, 'update'])->name('employee-shifts.update');
    Route::delete('/employee-shifts/{employeeShift}', [EmployeeShiftController::class, 'destroy'])->name('employee-shifts.destroy');
});


// UI
Route::get('/login', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('login');

// PROSES
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->middleware('auth')->name('dashboard');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');
