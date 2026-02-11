@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-black dark:text-white">
                Riwayat Absensi
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Daftar kehadiran berdasarkan hasil scan QR
            </p>
        </div>

        <nav class="text-sm">
            <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <li>
                    <a href="{{ route('dashboard') }}" class="hover:text-primary">
                        Dashboard
                    </a>
                </li>
                <li>/</li>
                <li class="font-medium text-primary">
                    Riwayat
                </li>
            </ol>
        </nav>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Pegawai
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Tanggal
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Check In
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Check Out
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Status
                    </th>
                    <th class="p-3 text-left font-semibold text-gray-700 dark:text-gray-200">
                        Jarak
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($history as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    {{-- Nama --}}
                    <td class="p-3 text-gray-800 dark:text-gray-100">
                        {{ $item->user->name ?? '-' }}
                    </td>
                    {{-- Tanggal --}}
                    <td class="p-3 text-gray-800 dark:text-gray-100">
                        <div class="flex flex-col">
                            <span class="font-medium">
                                {{ \Carbon\Carbon::parse($item->attendance_date)->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                QR: {{ $item->qrCode->code ?? '-' }}
                            </span>
                        </div>
                    </td>

                    {{-- Check In --}}
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $item->check_in
                            ? \Carbon\Carbon::parse($item->check_in)->timezone('Asia/Jakarta')->format('H:i')
                            : '--:--'
                        }}
                    </td>

                    {{-- Check Out --}}
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ $item->check_out
                            ? \Carbon\Carbon::parse($item->check_out)->timezone('Asia/Jakarta')->format('H:i')
                            : '--:--'
                        }}
                    </td>

                    {{-- Status --}}
                    <td class="p-3">
                        @if($item->status === 'IN_RADIUS')
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700
                                         dark:bg-green-900/30 dark:text-green-400">
                            Dalam Radius
                        </span>
                        @else
                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700
                                         dark:bg-red-900/30 dark:text-red-400">
                            Luar Radius
                        </span>
                        @endif
                    </td>

                    {{-- Jarak --}}
                    <td class="p-3 text-gray-700 dark:text-gray-300">
                        {{ number_format($item->distance, 0) }} m
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Belum ada data absensi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex flex-col items-center justify-between gap-4 md:flex-row">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Menampilkan
            <span class="font-medium text-black dark:text-white">
                {{ $history->firstItem() }} – {{ $history->lastItem() }}
            </span>
            dari
            <span class="font-medium text-black dark:text-white">
                {{ $history->total() }}
            </span>
            data
        </p>

        <div>
            {{ $history->links() }}
        </div>
    </div>
</div>
@endsection