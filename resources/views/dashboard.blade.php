@extends('layouts.app') @section('title', 'Ringkasan') @section('content')
    <section class="hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8"><span
                    class="badge text-bg-light text-primary mb-3">{{ auth()->user()->role === 'admin' ? 'DASHBOARD ADMIN' : 'DASHBOARD MAHASISWA' }}</span>
                <h1 class="display-6 fw-bold">Halo, {{ auth()->user()->name }} 👋</h1>
                <p class="mb-0 opacity-75">
                    {{ auth()->user()->role === 'admin' ? 'Kelola katalog, pantau permintaan, dan atur status peminjaman.' : 'Temukan bacaan baru dan pantau permintaan peminjamanmu.' }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0"><a class="btn btn-light btn-lg"
                    href="{{ route('books.index') }}">Jelajahi katalog <span aria-hidden="true">→</span></a></div>
        </div>
    </section>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card panel p-4">
                <div class="muted">Judul tersedia di katalog</div>
                <div class="display-5 fw-bold">{{ $bookCount }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card panel p-4">
                <div class="muted">{{ auth()->user()->role === 'admin' ? 'Permintaan menunggu' : 'Peminjaman aktif' }}</div>
                <div class="display-5 fw-bold">{{ $loanCount }}</div>
            </div>
        </div>
    </div>
    <div class="card panel p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h5 fw-bold mb-1">Aktivitas terbaru</h2><span
                    class="muted small">{{ auth()->user()->role === 'admin' ? 'Semua pengguna' : 'Akunmu' }}</span>
            </div><a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Buku</th>@if(auth()->user()->role === 'admin')
                        <th>Peminjam</th>@endif<th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>@forelse($recentLoans as $loan)
                    <tr>
                        <td>{{ $loan->book->title }}</td>@if(auth()->user()->role === 'admin')
                        <td>{{ $loan->user->name }}</td>@endif<td>{{ $loan->borrowed_at->format('d M Y') }}</td>
                        <td><span
                                class="badge text-bg-{{ $loan->status === 'disetujui' ? 'success' : ($loan->status === 'ditolak' ? 'danger' : 'warning') }}">{{ ucfirst($loan->status) }}</span>
                        </td>
                </tr>@empty<tr>
                        <td colspan="4" class="text-center muted py-4">Belum ada transaksi.</td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>
</div>@endsection