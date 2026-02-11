<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Models\QrCode;
use App\Models\EmployeeShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scan(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Session login habis, silakan login ulang'
            ], 401);
        }

        $request->validate([
            'qr_id'     => 'required|integer',
            'token'     => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $now = Carbon::now('Asia/Jakarta');

        // ================== VALIDASI QR ==================
        $qr = QrCode::where('id', $request->qr_id)
            ->where('is_active', true)
            ->whereDate('valid_date', $now->toDateString())
            ->first();

        if (!$qr) {
            return response()->json([
                'message' => 'QR tidak valid atau bukan untuk hari ini'
            ], 422);
        }

        // ================== AMBIL SHIFT USER ==================
        $employeeShift = EmployeeShift::with('shift')
            ->where('user_id', $user->id)
            ->whereDate('start_date', '<=', $now->toDateString())
            ->whereDate('end_date', '>=', $now->toDateString())
            ->first();

        if (!$employeeShift || !$employeeShift->shift) {
            return response()->json([
                'message' => 'Anda tidak memiliki shift hari ini'
            ], 422);
        }

        // ================== VALIDASI JAM SHIFT ==================
        $shiftStart = Carbon::parse($employeeShift->shift->start_time);
        $shiftEnd   = Carbon::parse($employeeShift->shift->end_time);

        // handle shift malam
        if ($shiftEnd->lessThan($shiftStart)) {
            $shiftEnd->addDay();
        }

        if ($now->lessThan($shiftStart) || $now->greaterThan($shiftEnd)) {
            return response()->json([
                'message' => 'Absen hanya bisa pada jam shift Anda (' .
                    $shiftStart->format('H:i') . ' - ' .
                    $shiftEnd->format('H:i') . ')'
            ], 422);
        }

        // ================== VALIDASI TOKEN QR ==================
        if (!$qr->isValidToken($request->token)) {
            return response()->json([
                'message' => 'QR sudah kadaluarsa atau token tidak valid'
            ], 422);
        }

        // ================== LOKASI KANTOR ==================
        $office = OfficeLocation::all()->sortBy(function ($office) use ($request) {
            return $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $office->latitude,
                $office->longitude
            );
        })->first();

        if (!$office) {
            return response()->json([
                'message' => 'Data lokasi kantor belum disetting'
            ], 500);
        }

        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        $status = $distance <= $office->radius ? 'IN_RADIUS' : 'OUT_RADIUS';

        // ================== LOGIKA ABSENSI ==================
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        // CHECK-IN
        if (!$attendance) {
            Attendance::create([
                'user_id'            => $user->id,
                'office_location_id' => $office->id,
                'qr_code_id'         => $qr->id,
                'attendance_date'    => $now->toDateString(),
                'check_in'           => $now,
                'scan_latitude'      => $request->latitude,
                'scan_longitude'     => $request->longitude,
                'distance'           => $distance,
                'status'             => $status,
            ]);

            return response()->json([
                'message' => 'Check-in berhasil'
            ]);
        }

        // CHECK-OUT
        if (!$attendance->check_out) {
            $attendance->update([
                'check_out' => $now
            ]);

            return response()->json([
                'message' => 'Check-out berhasil'
            ]);
        }

        return response()->json([
            'message' => 'Anda sudah melakukan check-in dan check-out hari ini'
        ], 422);
    }

    /**
     * Hitung jarak (Haversine Formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c);
    }
}
