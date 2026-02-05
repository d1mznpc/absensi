@extends('layouts.app')

@section('content')
<div
    x-data="employeeShiftCrud()"
    class="p-6 bg-white dark:bg-gray-900 rounded-xl shadow transition-all"
>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">
            Manage Employee Shifts
        </h1>

        <button
            @click="openCreate()"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
        >
            + Assign Shift
        </button>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Karyawan
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Shift
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Periode
                    </th>
                    <th class="p-3 text-center font-semibold text-gray-700 dark:text-gray-200 w-40">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($employeeShifts as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    <td class="p-3 text-gray-800 dark:text-gray-100">
                        {{ $item->user->name }}
                    </td>
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $item->shift->name }}
                    </td>
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $item->start_date->format('d M Y') }}
                        –
                        {{ $item->end_date->format('d M Y') }}
                    </td>
                    <td class="p-3">
                        <div class="flex justify-center gap-2">
                            <button
                                @click="openEdit(
                                    {{ $item->id }},
                                    {{ $item->user_id }},
                                    {{ $item->shift_id }},
                                    '{{ $item->start_date }}',
                                    '{{ $item->end_date }}'
                                )"
                                class="px-3 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded transition"
                            >
                                Edit
                            </button>

                            <button
                                @click="openDelete({{ $item->id }})"
                                class="px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded transition"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada data shift
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL -->
    <div
        x-show="modalOpen"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div
            @click.outside="closeModal"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-lg p-6"
        >

            <h2
                class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100"
                x-text="modalTitle">
            </h2>

            <!-- FORM -->
            <form :action="formAction" method="POST">
                @csrf

                <template x-if="method === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <template x-if="method === 'DELETE'">
                    <input type="hidden" name="_method" value="DELETE">
                </template>

                <!-- CREATE / EDIT -->
                <div x-show="method !== 'DELETE'">

                    <!-- Karyawan -->
                    <div class="mb-3">
                        <label class="text-sm text-gray-600 dark:text-gray-300">
                            Karyawan
                        </label>
                        <select
                            name="user_id"
                            x-model="form.user_id"
                            class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shift -->
                    <div class="mb-3">
                        <label class="text-sm text-gray-600 dark:text-gray-300">
                            Shift
                        </label>
                        <select
                            name="shift_id"
                            x-model="form.shift_id"
                            class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih Shift --</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">
                                    {{ $shift->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-3">
                        <label class="text-sm text-gray-600 dark:text-gray-300">
                            Tanggal Mulai
                        </label>
                        <input
                            type="date"
                            name="start_date"
                            x-model="form.start_date"
                            class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500"
                            required
                        >
                    </div>

                    <!-- End Date -->
                    <div class="mb-4">
                        <label class="text-sm text-gray-600 dark:text-gray-300">
                            Tanggal Selesai
                        </label>
                        <input
                            type="date"
                            name="end_date"
                            x-model="form.end_date"
                            class="w-full mt-1 border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:ring focus:ring-blue-500"
                            required
                        >
                    </div>
                </div>

                <!-- DELETE -->
                <div
                    x-show="method === 'DELETE'"
                    class="mb-4 text-gray-700 dark:text-gray-300"
                >
                    Yakin ingin menghapus shift ini?
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg transition"
                    >
                        Batal
                    </button>

                    <button
                        class="px-4 py-2 text-white rounded-lg transition"
                        :class="method === 'DELETE'
                            ? 'bg-red-600 hover:bg-red-700'
                            : 'bg-blue-600 hover:bg-blue-700'"
                    >
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- ALPINE SCRIPT (TIDAK DIUBAH) -->
<script>
function employeeShiftCrud() {
    return {
        modalOpen: false,
        modalTitle: '',
        formAction: '',
        method: 'POST',

        form: {
            user_id: '',
            shift_id: '',
            start_date: '',
            end_date: '',
        },

        openCreate() {
            this.modalTitle = 'Assign Shift';
            this.formAction = '{{ route("employee-shifts.store") }}';
            this.method = 'POST';
            this.form = { user_id: '', shift_id: '', start_date: '', end_date: '' };
            this.modalOpen = true;
        },

        openEdit(id, user_id, shift_id, start_date, end_date) {
            this.modalTitle = 'Edit Shift';
            this.formAction = `/admin/employee-shifts/${id}`;
            this.method = 'PUT';
            this.form = { user_id, shift_id, start_date, end_date };
            this.modalOpen = true;
        },

        openDelete(id) {
            this.modalTitle = 'Hapus Shift';
            this.formAction = `/admin/employee-shifts/${id}`;
            this.method = 'DELETE';
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
        }
    }
}
</script>
@endsection
