@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">

    {{-- FORM --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">
            {{ $editEmployee ? 'Edit Karyawan' : 'Tambah Karyawan' }}
        </h2>

        <form method="POST"
            action="{{ $editEmployee
                ? route('employees.update', $editEmployee->id)
                : route('employees.store') }}">
            @csrf
            @if($editEmployee)
                @method('PUT')
            @endif

            <div class="space-y-4">
                <input type="text" name="name"
                    value="{{ old('name', $editEmployee->name ?? '') }}"
                    placeholder="Nama"
                    class="w-full border rounded-lg px-4 py-2">

                <input type="email" name="email"
                    value="{{ old('email', $editEmployee->email ?? '') }}"
                    placeholder="Email"
                    class="w-full border rounded-lg px-4 py-2">

                @if(!$editEmployee)
                    <input type="password" name="password"
                        placeholder="Password"
                        class="w-full border rounded-lg px-4 py-2">
                @endif

                <div class="flex gap-2">
                    <button class="bg-brand-500 text-white px-4 py-2 rounded-lg">
                        {{ $editEmployee ? 'Update' : 'Simpan' }}
                    </button>

                    @if($editEmployee)
                        <a href="{{ route('employees.index') }}"
                           class="px-4 py-2 border rounded-lg">
                            Batal
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Data Karyawan</h2>

        <table class="w-full border rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $emp)
                <tr class="border-t">
                    <td class="p-3">{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td class="flex gap-2 p-3">
                        <a href="{{ route('employees.index', ['edit' => $emp->id]) }}"
                           class="text-blue-600">Edit</a>

                        <form method="POST"
                              action="{{ route('employees.destroy', $emp->id) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus karyawan?')"
                                class="text-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
