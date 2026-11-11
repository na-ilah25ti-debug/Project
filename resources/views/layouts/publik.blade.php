<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f5ef">
    <meta name="description" content="Lihat harga, fasilitas, dan kamar kos putri serta putra yang tersedia di KosKita.">
    <title>@yield('title', 'KosKita · Cari kamar kos')</title>
    <link rel="stylesheet" href="{{ asset('koskita/site.css') }}">
</head>
<body>
    <a class="skip-link" href="#konten">Ke konten utama</a>
    <header class="public-header">
        <div class="wrap public-nav">
            <x-kos-brand />
            <nav aria-label="Navigasi utama">
                <a href="{{ route('home') }}#rumah">Rumah kos</a>
                <a href="{{ route('home') }}#kamar">Cari kamar</a>
                <a href="{{ route('home') }}#pesan">Kontak</a>
            </nav>
            @guest
                <a class="btn ghost nav-action" href="{{ route('login') }}">Masuk penghuni <x-kos-icon name="external" :width="15" :height="15" /></a>
            @else
                <a class="btn ghost nav-action" href="{{ route('dashboard') }}">Dasbor <x-kos-icon name="external" :width="15" :height="15" /></a>
            @endguest
        </div>
    </header>
    @if (session('ok') || $errors->any())
        <div class="wrap"><x-kos-flash /></div>
    @endif
    <main id="konten">@yield('content')</main>
    <footer class="public-footer">
        <div class="wrap footer-inner">
            <x-kos-brand />
            <span>Kos putri &amp; putra</span>
            <a href="{{ route('home') }}#pesan">Hubungi pemilik <x-kos-icon name="external" :width="15" :height="15" /></a>
        </div>
    </footer>
</body>
</html>
