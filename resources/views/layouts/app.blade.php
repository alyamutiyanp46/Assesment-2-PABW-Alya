<!doctype html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPustaka') · SIPustaka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #13243a;
            --muted: #6f8093;
            --brand: #2962ff;
            --paper: #f4f7fb
        }

        body {
            background: var(--paper);
            color: var(--ink);
            font-family: Inter, system-ui, sans-serif
        }

        .navbar {
            background: #12243a
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: #356dff;
            display: grid;
            place-items: center;
            color: white;
            font-weight: 800
        }

        .hero {
            background: linear-gradient(120deg, #162f55, #2b68c9);
            color: white;
            border-radius: 24px
        }

        .panel {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 10px 34px #193a6410
        }

        .muted {
            color: var(--muted)
        }

        .book-cover {
            width: 38px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            background: #e8edf5
        }

        .btn-primary {
            background: var(--brand);
            border-color: var(--brand)
        }

        html[data-bs-theme=dark] body {
            background: #111b29;
            color: #edf3fa
        }

        html[data-bs-theme=dark] .panel,
        html[data-bs-theme=dark] .modal-content {
            background: #1b2939;
            color: #edf3fa
        }

        html[data-bs-theme=dark] .table {
            --bs-table-bg: transparent;
            --bs-table-color: #edf3fa
        }

        html[data-bs-theme=dark] .form-control,
        html[data-bs-theme=dark] .form-select {
            background: #101a27;
            color: #edf3fa;
            border-color: #3b4a5d
        }
    </style>@stack('head')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container"><a class="navbar-brand d-flex align-items-center gap-2 fw-bold"
                href="{{ auth()->check() ? route('dashboard') : route('login') }}"><span
                    class="brand-mark">S</span>SIPustaka</a>@auth<button class="navbar-toggler"
                            data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
                        <div id="nav" class="collapse navbar-collapse">
                            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2"><a class="nav-link"
                                    href="{{ route('dashboard') }}">Ringkasan</a><a class="nav-link"
                                    href="{{ route('books.index') }}">Katalog</a><a class="nav-link"
                                    href="{{ route('loans.index') }}">Peminjaman</a><span
                                    class="nav-link text-white-50">{{ auth()->user()->name }} ·
                                    {{ ucfirst(auth()->user()->role) }}</span><button id="theme-toggle"
                                    class="btn btn-sm btn-outline-light">◐ Tema</button>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button
                                        class="btn btn-sm btn-light ms-lg-2">Keluar</button></form>
                            </div>
                    </div>@endauth
        </div>
    </nav>
    <main class="container py-4 py-lg-5">@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close"
    data-bs-dismiss="alert"></button></div>@endif @yield('content')
    </main>
    <div id="toast-area" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tema tersimpan di localStorage. Loading bar dan toast memberi umpan balik AJAX.
        (() => { const root = document.documentElement, key = 'sipustaka-theme'; root.setAttribute('data-bs-theme', localStorage.getItem(key) || 'light'); document.getElementById('theme-toggle')?.addEventListener('click', () => { const n = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark'; root.setAttribute('data-bs-theme', n); localStorage.setItem(key, n) }) })();
        function toast(m, k = 'success') { const id = 't' + Date.now(); $('#toast-area').append(`<div id="${id}" class="toast text-bg-${k} border-0"><div class="d-flex"><div class="toast-body"></div><button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`); const el = document.getElementById(id); el.querySelector('.toast-body').textContent = m; new bootstrap.Toast(el).show(); el.addEventListener('hidden.bs.toast', () => el.remove()) }
        function showLoading(on) { if (on && !$('#loading-bar').length) $('body').append('<div id="loading-bar" style="position:fixed;inset:0 0 auto;height:3px;background:#2962ff;z-index:2000"></div>'); if (!on) $('#loading-bar').remove() }
    </script>@stack('scripts')
</body>

</html>