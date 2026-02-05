@extends('layouts.app')

@section('content')
<div
    x-data="employeeModal()"
    class="p-6 text-gray-800 dark:text-gray-200"
>

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            Data Karyawan
        </h2>

        <button
            @click="openCreate()"
            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg transition"
        >
            + Tambah Karyawan
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Nama
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Email
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Role
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200 w-40">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($employees as $emp)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="p-3 text-gray-800 dark:text-gray-100">
                        {{ $emp->name }}
                    </td>
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $emp->email }}
                    </td>
                    <td class="p-3 capitalize text-gray-700 dark:text-gray-300">
                        {{ $emp->role }}
                    </td>
                    <td class="p-3">
                        <div class="flex gap-4">
                            <a
                                href="#"
                                @click.prevent="openEdit(@js($emp))"
                                class="text-blue-600 dark:text-blue-400 hover:underline"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('employees.destroy', $emp->id) }}"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    onclick="return confirm('Hapus karyawan?')"
                                    class="text-red-600 dark:text-red-400 hover:underline"
                                >
                                    Hapus
                                </button>
                            </form>
                        </div>
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
        style="display:none"
    >
        <div
            @click.outside="close()"
            class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-lg p-6"
        >
            <h2
                class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100"
                x-text="isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'"
            ></h2>

            <form method="POST" :action="action">
                @csrf

                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-4">

                    <input
                        type="text"
                        name="name"
                        x-model="form.name"
                        placeholder="Nama"
                        class="w-full border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100
                               rounded-lg px-4 py-2
                               focus:ring focus:ring-brand-500"
                        required
                    >

                    <select
                        name="role"
                        x-model="form.role"
                        class="w-full border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100
                               rounded-lg px-4 py-2
                               focus:ring focus:ring-brand-500"
                        required
                    >
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="email"
                        name="email"
                        x-model="form.email"
                        placeholder="Email"
                        class="w-full border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100
                               rounded-lg px-4 py-2
                               focus:ring focus:ring-brand-500"
                        required
                    >

                    <template x-if="!isEdit">
                        <input
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-700
                                   text-gray-800 dark:text-gray-100
                                   rounded-lg px-4 py-2
                                   focus:ring focus:ring-brand-500"
                            required
                        >
                    </template>

                    <div class="flex justify-end gap-2 pt-2">
                        <button
                            type="button"
                            @click="close()"
                            class="px-4 py-2 rounded-lg
                                   bg-gray-200 hover:bg-gray-300
                                   dark:bg-gray-600 dark:hover:bg-gray-500
                                   transition"
                        >
                            Batal
                        </button>

                        <button
                            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg transition"
                        >
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
            role: '',
        },

        openCreate() {
            this.isEdit = false;
            this.action = "{{ route('employees.store') }}";
            this.form = { name: '', email: '', role: '' };
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
            this.open = false;
        }
    }
}
</script>
@endsection
