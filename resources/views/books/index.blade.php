@extends('layouts.app') @section('title', 'Katalog') @section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><span class="text-primary fw-semibold small">MODUL PUSTAKA</span>
            <h1 class="h2 fw-bold mb-1">Katalog buku</h1>
            <p class="muted mb-0">Cari buku berdasarkan judul, penulis, atau ISBN.</p>
        </div>@if(auth()->user()->role === 'admin')<button class="btn btn-primary" id="add-book">＋ Tambah buku</button>@endif
    </div>
    <div class="card panel p-3 p-lg-4">
        <form class="row g-2 mb-3" method="GET" action="{{ route('books.index') }}">
            <div class="col"><input class="form-control" name="q" value="{{ $q }}"
                    placeholder="Mis. Laravel, penulis, ISBN"></div>
            <div class="col-auto"><button class="btn btn-outline-primary">Cari</button></div>@if($q)
                <div class="col-auto"><a class="btn btn-outline-secondary" href="{{ route('books.index') }}">Hapus filter</a>
            </div>@endif
        </form>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>ISBN</th>
                        <th>Stok</th>@if(auth()->user()->role === 'admin')
                        <th class="text-end">Aksi</th>@endif
                    </tr>
                </thead>
                <tbody id="book-rows">@forelse($books as $book)
                    <tr id="book-{{ $book->id }}" data-id="{{ $book->id }}" data-title="{{ $book->title }}"
                        data-author="{{ $book->author }}" data-isbn="{{ $book->isbn }}" data-stock="{{ $book->stock }}"
                        data-category="{{ $book->category_id }}" data-description="{{ $book->description }}">
                        <td>
                            <div class="d-flex align-items-center gap-3">@if($book->cover_path)<img class="book-cover"
                            src="{{ route('books.cover', $book) }}">@else<span
                                    class="book-cover d-grid place-items-center">📘</span>@endif<div class="fw-semibold">
                                    {{ $book->title }}</div>
                            </div>
                        </td>
                        <td>{{ $book->category->name }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn ?: '—' }}</td>
                        <td><span class="badge text-bg-{{ $book->stock ? 'success' : 'secondary' }}">{{ $book->stock }}</span>
                        </td>@if(auth()->user()->role === 'admin')
                            <td class="text-end"><button class="btn btn-sm btn-outline-primary edit-book">Ubah</button> <button
                                    class="btn btn-sm btn-outline-danger delete-book"
                        data-url="{{ route('books.destroy', $book) }}">Hapus</button></td>@endif
                </tr>@empty<tr id="empty-row">
                        <td colspan="6" class="text-center muted py-5">Belum ada buku yang cocok.</td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $books->links() }}</div>
    </div>
    @if(auth()->user()->role === 'admin')
        <div class="modal fade" id="book-modal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="book-form" method="POST" enctype="multipart/form-data" action="{{ route('books.store') }}">
                        <div class="modal-header">
                            <div>
                                <h2 class="modal-title h5 fw-bold">Tambah buku</h2>
                                <div class="small muted">Validasi dijalankan di server melalui Form Request.</div>
                            </div><button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                        </div>
                        <div class="modal-body">
                            <div id="form-errors" class="alert alert-danger d-none"></div>
                            <div class="row g-3">
                                <div class="col-md-8"><label class="form-label">Judul</label><input name="title"
                                        class="form-control" required maxlength="180"></div>
                                <div class="col-md-4"><label class="form-label">Kategori</label><select name="category_id"
                                        class="form-select" required>@foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach
                                    </select></div>
                                <div class="col-md-6"><label class="form-label">Penulis</label><input name="author"
                                        class="form-control" required></div>
                                <div class="col-md-6"><label class="form-label">ISBN</label><input name="isbn"
                                        class="form-control"></div>
                                <div class="col-md-4"><label class="form-label">Stok</label><input name="stock" type="number"
                                        min="0" value="1" class="form-control" required></div>
                                <div class="col-md-8"><label class="form-label">Sampul (maks. 2 MB)</label><input name="cover"
                                        type="file" accept="image/*" class="form-control"></div>
                                <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description"
                                        rows="3" class="form-control"></textarea></div>
                            </div>
                        </div>
                        <div class="modal-footer"><button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"
                                id="save-book">Simpan</button></div>
                    </form>
                </div>
            </div>
    </div>@endif
@endsection
@push('scripts')
    @if(auth()->user()->role === 'admin')
        <script>
            // FormData membawa field biasa dan file sampul. Header Accept meminta respons JSON.
            const bookModal = new bootstrap.Modal('#book-modal'), bookForm = document.getElementById('book-form');
            function resetBook() { bookForm.reset(); bookForm.action = '{{ route('books.store') }}'; document.querySelector('#book-form .modal-title').textContent = 'Tambah buku'; document.getElementById('form-errors').classList.add('d-none') }
            function upsertBook(b) { let row = document.getElementById('book-' + b.id); if (!row) { $('#empty-row').remove(); row = document.createElement('tr'); row.id = 'book-' + b.id; row.dataset.id = b.id; row.innerHTML = '<td><div class="d-flex align-items-center gap-3"><span class="book-cover d-grid place-items-center">📘</span><div class="fw-semibold"></div></div></td><td></td><td></td><td></td><td></td><td class="text-end"><button class="btn btn-sm btn-outline-primary edit-book">Ubah</button> <button class="btn btn-sm btn-outline-danger delete-book">Hapus</button></td>'; document.getElementById('book-rows').prepend(row) } row.dataset.title = b.title; row.dataset.author = b.author; row.dataset.isbn = b.isbn || ''; row.dataset.stock = b.stock; row.dataset.category = b.category_id; row.dataset.description = b.description || ''; row.querySelector('td:nth-child(1) .fw-semibold').textContent = b.title; row.querySelector('td:nth-child(2)').textContent = b.category.name; row.querySelector('td:nth-child(3)').textContent = b.author; row.querySelector('td:nth-child(4)').textContent = b.isbn || '—'; row.querySelector('td:nth-child(5)').textContent = b.stock; const oldCover = row.querySelector('td:first-child .book-cover'); if (b.cover_path) { const image = document.createElement('img'); image.className = 'book-cover'; image.alt = 'Sampul ' + b.title; image.src = '/books/' + b.id + '/cover?v=' + Date.now(); oldCover.replaceWith(image) } else if (oldCover.tagName === 'IMG') { const placeholder = document.createElement('span'); placeholder.className = 'book-cover d-grid place-items-center'; placeholder.textContent = '📘'; oldCover.replaceWith(placeholder) } row.querySelector('.delete-book').dataset.url = '/books/' + b.id }
            document.getElementById('add-book').onclick = () => { resetBook(); bookModal.show() };
            $(document).on('click', '.edit-book', function () { const row = this.closest('tr'); resetBook(); bookForm.action = '/books/' + row.dataset.id; document.querySelector('#book-form .modal-title').textContent = 'Ubah buku'; bookForm.elements.namedItem('title').value = row.dataset.title; bookForm.elements.namedItem('author').value = row.dataset.author; bookForm.elements.namedItem('isbn').value = row.dataset.isbn; bookForm.elements.namedItem('stock').value = row.dataset.stock; bookForm.elements.namedItem('category_id').value = row.dataset.category; bookForm.elements.namedItem('description').value = row.dataset.description; bookModal.show() });
            $('#book-form').on('submit', async function (e) { e.preventDefault(); const fd = new FormData(this); if (this.action.includes('/books/') && !fd.has('_method')) fd.append('_method', 'PUT'); showLoading(true); $('#save-book').prop('disabled', true); try { const res = await fetch(this.action, { method: 'POST', body: fd, headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } }); const data = await res.json(); if (!res.ok) { const box = $('#form-errors').removeClass('d-none').empty(); if (data.errors) { Object.values(data.errors).flat().forEach(m => box.append($('<div>').text(m))) } else box.text(data.message || 'Data gagal disimpan.'); return } toast(data.message); if (data.book) upsertBook(data.book); bookModal.hide() } catch (err) { toast('Tidak dapat terhubung ke server. Periksa koneksi dan Console.', 'danger') } finally { showLoading(false); $('#save-book').prop('disabled', false) } });
            $(document).on('click', '.delete-book', async function () { if (!confirm('Hapus buku ini?')) return; showLoading(true); try { const res = await fetch(this.dataset.url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/x-www-form-urlencoded' }, body: '_method=DELETE' }); const data = await res.json(); if (!res.ok) throw new Error(data.message); document.getElementById('book-' + this.closest('tr').dataset.id).remove(); toast(data.message) } catch (e) { toast(e.message || 'Gagal menghapus buku.', 'danger') } finally { showLoading(false) } });
        </script>
    @endif
@endpush