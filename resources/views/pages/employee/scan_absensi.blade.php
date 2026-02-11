@extends('layouts.app')

@section('content')
<div class="mx-auto flex min-h-screen max-w-screen-xl items-center justify-center px-4">

    <div
        class="w-full max-w-md rounded-xl border border-stroke bg-white shadow-default
               dark:border-strokedark dark:bg-boxdark">

        {{-- Header --}}
        <div class="border-b border-stroke px-6 py-5 dark:border-strokedark">
            <h2 class="text-lg font-bold text-black dark:text-white">
                Scan QR Absensi
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Pastikan kamera mengarah ke QR Code absensi
            </p>
        </div>

        {{-- Content --}}
        <div class="p-6 space-y-4">

            {{-- Camera --}}
            <div
                class="relative overflow-hidden rounded-lg border border-stroke
                       bg-gray-50 dark:border-strokedark dark:bg-meta-4">
                <div id="reader" class="aspect-square w-full"></div>

                {{-- Center Guide --}}
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                    <div
                        class="rounded-lg border border-white/30 bg-black/50 px-3 py-1
                               text-xs text-white">
                        Posisikan QR di tengah
                    </div>
                </div>
            </div>

            {{-- Loading --}}
            <div
                id="loading"
                class="hidden flex items-center justify-center gap-2 rounded-md
                       bg-primary/10 px-4 py-2 text-sm text-primary">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                        fill="none" />
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
                Memproses absensi...
            </div>

            {{-- Result --}}
            <div
                id="result"
                class="hidden rounded-md px-4 py-3 text-center text-sm font-medium"></div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    const resultBox = document.getElementById('result');
    const loadingBox = document.getElementById('loading');

    function showResult(message, success = true) {
        resultBox.className =
            'rounded-md px-4 py-3 text-center text-sm font-medium ' +
            (success ?
                'bg-success/10 text-success' :
                'bg-danger/10 text-danger');
        resultBox.innerText = message;
        resultBox.classList.remove('hidden');
    }

    function sendAttendance(qrId, token, latitude, longitude) {
        loadingBox.classList.remove('hidden');

        fetch("{{ route('absen.scan') }}", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },

                body: JSON.stringify({
                    qr_id: qrId,
                    token: token,
                    latitude: latitude,
                    longitude: longitude
                })
            })

            .then(async res => {
                const text = await res.text();

                try {
                    const data = JSON.parse(text);
                    loadingBox.classList.add('hidden');
                    showResult(data.message ?? 'Respon tidak dikenali', res.ok);
                } catch (e) {
                    loadingBox.classList.add('hidden');
                    console.error(text);
                    showResult('Server error (bukan JSON)', false);
                }
            })
            .catch(() => {
                loadingBox.classList.add('hidden');
                showResult('Gagal mengirim data absensi', false);
            });
    }

    function onScanSuccess(decodedText) {
        let data;

        try {
            data = JSON.parse(decodedText);
        } catch {
            showResult('QR tidak valid', false);
            return;
        }

        if (!data.qr_id || !data.token) {
            showResult('QR tidak dikenali', false);
            return;
        }

        html5QrCode.stop();

        if (!navigator.geolocation) {
            showResult('Geolocation tidak didukung browser', false);
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                sendAttendance(
                    data.qr_id,
                    data.token,
                    position.coords.latitude,
                    position.coords.longitude
                );
            },
            () => {
                showResult('Lokasi tidak diizinkan', false);
            }
        );
    }

    const html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start({
            facingMode: "environment"
        }, {
            fps: 10,
            qrbox: 250
        },
        onScanSuccess
    );
</script>
@endsection