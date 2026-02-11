<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\QrCode;
use App\Models\Attendance;
use App\Http\Controllers\AttendanceHistoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/qr-token/{qr}', function (QrCode $qr) {
    abort_unless($qr->is_active, 404);

    return response()->json([
        'qr_id' => $qr->id,
        'token' => $qr->generateToken(),
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/attendance/history', [AttendanceHistoryController::class, 'index']);
    Route::get('/attendance/history/{id}', [AttendanceHistoryController::class, 'show']);
});