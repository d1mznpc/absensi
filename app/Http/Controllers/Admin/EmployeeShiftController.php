<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeShift;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;

class EmployeeShiftController extends Controller
{
    public function index()
    {
        $employeeShifts = EmployeeShift::with(['user', 'shift'])
            ->orderBy('start_date', 'desc')
            ->get();

        $users  = User::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get();

        return view('pages.admin.employee_shift', compact(
            'employeeShifts',
            'users',
            'shifts'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'shift_id'   => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        // 🔐 CEK BENTROK RANGE TANGGAL
        $exists = EmployeeShift::where('user_id', $request->user_id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'start_date' => 'Shift karyawan bentrok dengan tanggal lain.'
            ]);
        }

        EmployeeShift::create([
            'user_id'    => $request->user_id,
            'shift_id'   => $request->shift_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        return back()->with('success', 'Shift berhasil ditambahkan.');
    }

    public function update(Request $request, EmployeeShift $employeeShift)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'shift_id'   => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $exists = EmployeeShift::where('user_id', $request->user_id)
            ->where('id', '!=', $employeeShift->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'start_date' => 'Shift karyawan bentrok dengan tanggal lain.'
            ]);
        }

        $employeeShift->update([
            'user_id'    => $request->user_id,
            'shift_id'   => $request->shift_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        return back()->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(EmployeeShift $employeeShift)
    {
        $employeeShift->delete();

        return back()->with('success', 'Shift berhasil dihapus.');
    }
}
