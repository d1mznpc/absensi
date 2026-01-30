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
        $employees = User::latest()->get();
        $editEmployee = null;

        if ($request->has('edit')) {
            $editEmployee = User::findOrFail($request->edit);
        }

        // Role options untuk dropdown di form
        $roles = [
            'user' => 'User',
            'admin' => 'Admin',
        ];

        return view('pages.admin.employees.index', compact('employees', 'editEmployee', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin', // validasi role
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,manager',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Data karyawan diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Karyawan dihapus');
    }
}
