<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $history = Attendance::with(['user', 'qrCode', 'logs'])
            ->where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->paginate(10);

        return view('pages.history', compact('history'));
    }
}
