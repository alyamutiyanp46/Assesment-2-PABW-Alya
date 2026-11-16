# SIPustaka - Jalur A (Laravel HMVC + Blade + jQuery)

Project latihan asesmen Praktikum PABW. Contoh domainnya perpustakaan: kategori memiliki banyak buku; buku dan pengguna menjadi relasi pada transaksi peminjaman. Source diberi komentar bahasa Indonesia untuk membantu memahami alur.

## Persiapan

1. Pastikan PHP 8.2+, Composer, Node.js/NPM dan MySQL aktif (Laragon bisa dipakai).
2. Buat database MySQL `sipustaka`.
3. Salin `.env.example` menjadi `.env`; pastikan `DB_DATABASE=sipustaka`, `DB_USERNAME`, dan `DB_PASSWORD` cocok dengan MySQL lokal.
4. Jalankan:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Buka `http://127.0.0.1:8000`. Akun seed: `admin@sipustaka.test` / `password`, dan `mahasiswa@sipustaka.test` / `password`. Ganti kata sandi demo sebelum penggunaan lain.

## Alur yang dicakup

- Register, login, logout; seeder role admin dan mahasiswa.
- Dashboard berubah menurut role; middleware role menjaga route dan mengembalikan 403.
- Modul `Pustaka`: migration, model Eloquent, Form Request, controller, route, Blade view.
- CRUD buku dengan pencarian, pagination, upload sampul melalui Storage dan delete confirmation.
- CRUD peminjaman yang berelasi ke buku dan pengguna.
- Endpoint JSON dipakai jQuery + `FormData` tanpa refresh, termasuk indikator loading dan notifikasi.
- Layout responsif, Bootstrap 5 CDN, navbar, dan toggle tema gelap.

## Catatan HMVC

Domain utama diletakkan di `Modules/Pustaka`. Provider modul memuat route serta migration modul. Di Laravel Modules v12, kelas modul juga didaftarkan lewat PSR-4 root composer supaya dapat dipahami tanpa trik autoload tersembunyi. Paket `nwidart/laravel-modules` tetap menjadi dasar pembentukan dan pengelolaan modul.

## Urutan saat latihan

`Route -> middleware -> controller -> Form Request -> Eloquent model -> MySQL -> Blade/JSON -> jQuery`

Lihat komentar `//` dan `{{-- ... --}}` di file source untuk hubungan antarbagian. Jalur A memakai Bootstrap dan jQuery dari CDN; koneksi internet diperlukan untuk memuat keduanya. Ini bahan belajar sebelum asesmen; saat ujian ikuti aturan dosen tentang penggunaan AI.
