<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('pages.admin.shift', compact('shifts'));
    }


    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);

        Shift::create($request->all());

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift berhasil ditambahkan');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);

        $shift->update($request->all());

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift berhasil diperbarui');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()
            ->route('shifts.index')
            ->with('success', 'Shift berhasil dihapus');
    }
}
