@extends('layouts.app')

@section('content')
<div
    x-data="employeeModal()"
    class="p-6 text-gray-800 dark:text-gray-200">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Data Karyawan</h2>

        <button
            @click="openCreate()"
            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg">
            + Tambah Karyawan
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="text-left">Email</th>
                    <th class="text-left">Role</th> {{-- Tambahkan kolom Role --}}
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $emp)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="p-3">{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td class="capitalize">{{ $emp->role }}</td> {{-- Tampilkan role --}}
                    <td class="flex gap-3 p-3">
                        <a href="#"
                            @click.prevent="openEdit(@js($emp))"
                            class="text-blue-600 dark:text-blue-400 hover:underline">
                            Edit
                        </a>

                        <form method="POST"
                            action="{{ route('employees.destroy', $emp->id) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                onclick="return confirm('Hapus karyawan?')"
                                class="text-red-600 dark:text-red-400 hover:underline">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        style="display:none">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4"
                x-text="isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'">
            </h2>

            <form method="POST" :action="action">
                @csrf

                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-4">
                    <input type="text"
                        name="name"
                        x-model="form.name"
                        placeholder="Nama"
                        class="w-full border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-200
                               rounded-lg px-4 py-2"
                        required>

                    <select name="role" x-model="form.role"
                        class="w-full border border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-700
               text-gray-800 dark:text-gray-200
               rounded-lg px-4 py-2" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $key => $label)
                        <option :selected="form.role === '{{ $key }}'" value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <input type="email"
                        name="email"
                        x-model="form.email"
                        placeholder="Email"
                        class="w-full border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-200
                               rounded-lg px-4 py-2"
                        required>

                    <template x-if="!isEdit">
                        <input type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-800 dark:text-gray-200
                                   rounded-lg px-4 py-2"
                            required>
                    </template>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="close()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600
                                   rounded-lg
                                   bg-white dark:bg-gray-700
                                   hover:bg-gray-100 dark:hover:bg-gray-600">
                            Batal
                        </button>

                        <button
                            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ALPINE SCRIPT --}}
<script>
    function employeeModal() {
        return {
            open: false,
            isEdit: false,
            action: '',
            form: {
                name: '',
                email: '',
            },

            form: {
                name: '',
                email: '',
                role: '',
            },

            openCreate() {
                this.isEdit = false;
                this.action = "{{ route('employees.store') }}";
                this.form = {
                    name: '',
                    email: '',
                    role: ''
                };
                this.open = true;
            },

            openEdit(emp) {
                this.isEdit = true;
                this.action = `/employees/${emp.id}`;
                this.form = {
                    name: emp.name,
                    email: emp.email,
                    role: emp.role,
                };
                this.open = true;
            },

            close() {
                this.open = false
            }
        }
    }
</script>
@endsection