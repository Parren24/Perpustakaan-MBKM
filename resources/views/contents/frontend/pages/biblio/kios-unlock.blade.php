{{-- resources/views/contents/frontend/pages/biblio/kios-unlock.blade.php --}}
@extends('layouts.frontend.main')
@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height:70vh;">
    <div class="card p-4 shadow" style="width:320px;">
        <h5 class="text-center mb-3">Masukkan PIN Kios</h5>
        <input type="password" id="kiosPinInput" class="form-control text-center mb-3" maxlength="10" inputmode="numeric" autofocus>
        <button id="submitPinBtn" class="btn btn-primary w-100">Aktivasi Device</button>
        <div id="pinError" class="text-danger text-center mt-2"></div>
    </div>
</div>

<script>
document.getElementById('submitPinBtn').addEventListener('click', () => {
    fetch('{{ route("frontend.biblio.kios.unlock.submit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pin: document.getElementById('kiosPinInput').value })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('pinError').textContent = data.message;
        }
    });
});
</script>
@endsection