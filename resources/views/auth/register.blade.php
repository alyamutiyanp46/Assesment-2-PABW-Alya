@extends('layouts.app') @section('title', 'Daftar') @section('content')
    <div class="row justify-content-center py-lg-5">
        <div class="col-md-7 col-lg-5">
            <div class="card panel p-4 p-md-5">
                <h1 class="h3 fw-bold">Buat akun mahasiswa</h1>
                <p class="muted mb-4">Akun pendaftaran publik memiliki role mahasiswa.</p>
                <form method="POST" action="{{ route('register.store') }}">@csrf
                    @foreach(['name' => 'Nama lengkap', 'email' => 'Email'] as $field => $label)
                        <div class="mb-3"><label class="form-label">{{ $label }}</label><input class="form-control"
                                name="{{ $field }}" value="{{ old($field) }}" {{ $field === 'email' ? 'type=email' : 'type=text' }}
                                required>@error($field)
                                <div class="text-danger small">{{ $message }}</div>@enderror
                    </div>@endforeach
                    <div class="mb-3"><label class="form-label">Kata sandi</label><input class="form-control"
                            type="password" name="password" required>@error('password')
                            <div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4"><label class="form-label">Ulangi kata sandi</label><input class="form-control"
                            type="password" name="password_confirmation" required></div><button
                        class="btn btn-primary w-100">Daftar</button>
                </form>
                <p class="text-center mt-4 mb-0">Sudah terdaftar? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
        </div>
</div>@endsection