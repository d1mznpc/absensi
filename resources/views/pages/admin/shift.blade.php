@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">
        Manajemen Shift
    </h1>

    {{-- FLASH --}}
    @if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 p-3">
        {{ session('success') }}
    </div>
    @endif

    {{-- BUTTON TAMBAH --}}
    <button
        type="button"
        onclick="openCreateModal()"
        class="mb-4 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
        + Tambah Shift
    </button>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
        <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg">

            {{-- HEADER --}}
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                        Nama Shift
                    </th>
                    <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                        Jam Masuk
                    </th>
                    <th class="p-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                        Jam Pulang
                    </th>
                    <th class="p-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-40">
                        Aksi
                    </th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($shifts as $shift)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="p-3 text-gray-800 dark:text-gray-100">
                        {{ $shift->name }}
                    </td>

                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $shift->start_time }}
                    </td>

                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $shift->end_time }}
                    </td>

                    <td class="p-3">
                        <div class="flex justify-center gap-4 text-sm font-medium">
                            <button
                                type="button"
                                data-id="{{ $shift->id }}"
                                data-name="{{ $shift->name }}"
                                data-start="{{ $shift->start_time }}"
                                data-end="{{ $shift->end_time }}"
                                onclick="openEditModal(this)"
                                class="text-blue-600 dark:text-blue-400 hover:underline">
                                Edit
                            </button>

                            <button
                                type="button"
                                onclick="openDeleteModal({{ $shift->id }})"
                                class="text-red-600 dark:text-red-400 hover:underline">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= CREATE MODAL ================= --}}
<div id="createModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
    <div class="w-full max-w-md rounded-lg bg-white dark:bg-gray-800 p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
            Tambah Shift
        </h2>

        <form action="{{ route('shifts.store') }}" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Nama Shift"
                class="mb-3 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <input type="time" name="start_time"
                class="mb-3 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <input type="time" name="end_time"
                class="mb-4 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeCreateModal()"
                    class="px-4 py-2 rounded border dark:border-gray-600">
                    Batal
                </button>
                <button class="px-4 py-2 rounded bg-blue-600 text-white">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= EDIT MODAL ================= --}}
<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
    <div class="w-full max-w-md rounded-lg bg-white dark:bg-gray-800 p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
            Edit Shift
        </h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="name" id="editName"
                class="mb-3 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <input type="time" name="start_time" id="editStart"
                class="mb-3 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <input type="time" name="end_time" id="editEnd"
                class="mb-4 w-full rounded border p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 rounded border dark:border-gray-600">
                    Batal
                </button>
                <button class="px-4 py-2 rounded bg-green-600 text-white">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= DELETE MODAL ================= --}}
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">
    <div class="w-full max-w-sm rounded-lg bg-white dark:bg-gray-800 p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
            Hapus Shift?
        </h2>

        <p class="mb-4 text-gray-600 dark:text-gray-300">
            Data shift akan dihapus permanen.
        </p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded border dark:border-gray-600">
                    Batal
                </button>
                <button class="px-4 py-2 rounded bg-red-600 text-white">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
    function openCreateModal() {
        toggleModal('createModal', true)
    }

    function closeCreateModal() {
        toggleModal('createModal', false)
    }

    function openEditModal(el) {
        toggleModal('editModal', true)

        document.getElementById('editForm').action = `/admin/shifts/${el.dataset.id}`
        document.getElementById('editName').value = el.dataset.name
        document.getElementById('editStart').value = el.dataset.start
        document.getElementById('editEnd').value = el.dataset.end
    }

    function closeEditModal() {
        toggleModal('editModal', false)
    }

    function openDeleteModal(id) {
        toggleModal('deleteModal', true)
        document.getElementById('deleteForm').action = `/admin/shifts/${id}`
    }

    function closeDeleteModal() {
        toggleModal('deleteModal', false)
    }

    function toggleModal(id, show) {
        const modal = document.getElementById(id)
        modal.classList.toggle('hidden', !show)
        modal.classList.toggle('flex', show)
    }
</script>
@endsection