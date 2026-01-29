<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = User::where('role', 'user')->latest()->get();
        $editEmployee = null;

        if ($request->has('edit')) {
            $editEmployee = User::findOrFail($request->edit);
        }

        return view('pages.admin.employees.index', compact('employees', 'editEmployee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('employees.index')
            ->with('success', 'Data karyawan diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Karyawan dihapus');
    }
}

