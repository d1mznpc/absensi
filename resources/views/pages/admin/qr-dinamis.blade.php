@extends('layouts.app')

@section('content')
<div class="p-6 text-center">
    <h1 class="text-xl font-bold mb-4">QR Absensi Dinamis</h1>

    <img id="qrImage" class="mx-auto" />

    <p class="text-sm text-gray-500 mt-2">
        QR berubah tiap 3 detik
    </p>
</div>

<script>
    const qrId = {{ $qr->id }};
    const qrImg = document.getElementById('qrImage');

    function refreshQr() {
        fetch(`/api/qr-token/${qrId}`)
            .then(res => res.json())
            .then(data => {
                const payload = JSON.stringify(data);

                qrImg.src =
                    `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(payload)}`;
            });
    }

    refreshQr();
    setInterval(refreshQr, 3000);
</script>
@endsection
