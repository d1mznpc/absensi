<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OfficeLocation;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Proses check-in / check-out
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code'   => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();

        // ================== VALIDASI QR ==================
        $qr = QrCode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->whereDate('valid_date', now())
            ->first();

        if (!$qr) {
            return response()->json([
                'message' => 'QR Code tidak valid atau sudah kadaluarsa'
            ], 422);
        }

        // ================== LOKASI KANTOR ==================
        $office = OfficeLocation::first(); // atau by ID jika multi kantor

        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        $status = $distance <= $office->radius ? 'IN_RADIUS' : 'OUT_RADIUS';

        // ================== CEK ABSENSI HARI INI ==================
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', today())
            ->first();

        // ================== CHECK IN ==================
        if (!$attendance) {
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'office_location_id' => $office->id,
                'qr_code_id' => $qr->id,
                'attendance_date' => today(),
                'check_in' => now(),
                'scan_latitude' => $request->latitude,
                'scan_longitude' => $request->longitude,
                'distance' => $distance,
                'status' => $status,
            ]);

            return response()->json([
                'message' => 'Check-in berhasil',
                'status' => $status,
                'distance' => $distance
            ]);
        }

        // ================== CHECK OUT ==================
        if ($attendance && !$attendance->check_out) {
            $attendance->update([
                'check_out' => now(),
            ]);

            return response()->json([
                'message' => 'Check-out berhasil',
            ]);
        }

        return response()->json([
            'message' => 'Anda sudah check-in dan check-out hari ini'
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
