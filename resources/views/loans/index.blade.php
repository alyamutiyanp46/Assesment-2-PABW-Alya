@extends('layouts.app') @section('title', 'Peminjaman') @section('content')
    <div class="mb-4"><span class="text-primary fw-semibold small">MODUL TRANSAKSI</span>
        <h1 class="h2 fw-bold mb-1">Peminjaman</h1>
        <p class="muted mb-0">Permintaan terhubung ke buku dan akun pengguna melalui foreign key.</p>
    </div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card panel p-4">
                <h2 class="h5 fw-bold">Ajukan peminjaman</h2>
                <p class="small muted">Admin dapat mengelola transaksi semua pengguna. Akun mahasiswa hanya melihat
                    transaksinya.</p>
                <form method="POST" action="{{ route('loans.store') }}">@csrf<div class="mb-3"><label
                            class="form-label">Buku</label><select name="book_id" class="form-select" required>
                            <option value="">Pilih buku</option>@foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }} (stok {{ $book->stock }})</option>@endforeach
                        </select>@error('book_id')
                        <div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3"><label class="form-label">Tanggal pinjam</label><input type="date" name="borrowed_at"
                            value="{{ old('borrowed_at', now()->format('Y-m-d')) }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Tanggal kembali</label><input type="date" name="due_at"
                            value="{{ old('due_at', now()->addDays(7)->format('Y-m-d')) }}" class="form-control"
                            required>@error('due_at')
                            <div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3"><label class="form-label">Catatan</label><textarea name="notes" class="form-control"
                            rows="2">{{ old('notes') }}</textarea></div><button class="btn btn-primary w-100">Kirim
                        permintaan</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card panel p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Buku</th>@if(auth()->user()->role === 'admin')
                                <th>Peminjam</th>@endif<th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>@forelse($loans as $loan)
                            <tr>
                                <td class="fw-semibold">{{ $loan->book->title }}</td>@if(auth()->user()->role === 'admin')
                                <td>{{ $loan->user->name }}</td>@endif<td class="small">
                                    {{ $loan->borrowed_at->format('d/m/Y') }}<br><span class="muted">s.d.
                                        {{ $loan->due_at->format('d/m/Y') }}</span></td>
                                <td><span
                                        class="badge text-bg-{{ $loan->status === 'disetujui' ? 'success' : ($loan->status === 'ditolak' ? 'danger' : ($loan->status === 'dikembalikan' ? 'secondary' : 'warning')) }}">{{ ucfirst($loan->status) }}</span>
                                </td>
                                <td class="text-nowrap">@if(auth()->user()->role === 'admin' && $loan->status !== 'dikembalikan')
                                    <form method="POST" action="{{ route('loans.update', $loan) }}" class="d-inline">@csrf
                                        @method('PATCH')<input type="hidden" name="book_id"
                                            value="{{ $loan->book_id }}"><input type="hidden" name="borrowed_at"
                                            value="{{ $loan->borrowed_at->format('Y-m-d') }}"><input type="hidden"
                                            name="due_at" value="{{ $loan->due_at->format('Y-m-d') }}"><select name="status"
                                            class="form-select form-select-sm d-inline-block" style="width:auto"
                                            onchange="if(confirm('Simpan perubahan status?'))this.form.submit();else this.value='{{ $loan->status }}'">
                                            <option value="menunggu" @selected($loan->status === 'menunggu')>Menunggu</option>
                                            <option value="disetujui" @selected($loan->status === 'disetujui')>Setujui
                                            </option>
                                            <option value="ditolak" @selected($loan->status === 'ditolak')>Tolak</option>
                                            <option value="dikembalikan">Dikembalikan</option>
                                </select></form>@endif
                                    @if(auth()->user()->role === 'admin' || ($loan->user_id === auth()->id() && $loan->status === 'menunggu'))
                                        <form method="POST" action="{{ route('loans.destroy', $loan) }}" class="d-inline"
                                            onsubmit="return confirm('Hapus transaksi ini?')">@csrf @method('DELETE')<button
                                    class="btn btn-sm btn-outline-danger">Hapus</button></form>@endif
                                </td>
                        </tr>@empty<tr>
                                <td colspan="5" class="text-center muted py-5">Belum ada peminjaman.</td>
                            </tr>@endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $loans->links() }}</div>
            </div>
        </div>
</div>@endsection