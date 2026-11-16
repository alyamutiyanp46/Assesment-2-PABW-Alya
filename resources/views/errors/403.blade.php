@extends('layouts.app') @section('title', '403 - Akses ditolak') @section('content')
    <div class="card panel p-5 text-center mx-auto" style="max-width:620px">
        <div class="display-2 fw-bold text-primary">403</div>
        <h1 class="h3 fw-bold">Akses ditolak</h1>
        <p class="muted">Akunmu sudah masuk, tetapi role yang dimiliki tidak memiliki izin untuk membuka halaman ini.</p><a
            class="btn btn-primary mx-auto" href="{{ route('dashboard') }}">Kembali ke dashboard</a>
</div>@endsection