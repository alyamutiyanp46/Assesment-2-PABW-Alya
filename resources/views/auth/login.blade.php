@extends('layouts.app') @section('title', 'Masuk') @section('content')
    <div class="row justify-content-center py-lg-5">
        <div class="col-md-7 col-lg-5">
            <div class="card panel p-4 p-md-5">
                <div class="brand-mark mb-3">S</div>
                <p class="text-primary fw-semibold mb-1">Sistem Perpustakaan</p>
                <h1 class="h3 fw-bold">Selamat datang kembali</h1>
                <p class="muted mb-4">Masuk untuk melihat katalog dan transaksi peminjaman.</p>
                <form method="POST" action="{{ route('login.store') }}">@csrf<div class="mb-3"><label
                            class="form-label">Email</label><input class="form-control form-control-lg" type="email"
                            name="email" value="{{ old('email') }}" required autofocus>@error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3"><label class="form-label">Kata sandi</label><input
                            class="form-control form-control-lg" type="password" name="password" required>@error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember"
                            id="remember"><label class="form-check-label" for="remember">Ingat saya</label></div><button
                        class="btn btn-primary btn-lg w-100">Masuk</button>
                </form>
                <p class="text-center mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
                <div class="small muted mt-4">Demo: admin@sipustaka.test / password</div>
            </div>
        </div>
</div>@endsection