<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KosKita')</title>
    <link rel="stylesheet" href="{{ asset('koskita/site.css') }}">
    <link rel="stylesheet" href="{{ asset('koskita/portal.css') }}">
    <script src="{{ asset('koskita/portal.js') }}" defer></script>
</head>
<body>
    @php
        $owner = auth()->user()->role === 'pemilik';
        $nav = $owner ? [
            ['admin.dashboard', 'Ringkasan', 'grid', 'admin.dashboard'],
            ['kamar.index', 'Kamar', 'bed', 'kamar.*'],
            ['penghuni.index', 'Penghuni', 'users', 'penghuni.*'],
            ['tagihan.index', 'Tagihan', 'wallet', 'tagihan.*'],
            ['pesan.index', 'Pesan masuk', 'chat', 'pesan.*'],
            ['laporan.index', 'Laporan', 'tool', 'laporan.*'],
            ['galon.index', 'Pesanan galon', 'drop', 'galon.*'],
            ['foto.index', 'Foto', 'photo', 'foto.*'],
        ] : [
            ['anak.home', 'Ringkasan', 'grid', 'anak.home'],
            ['anak.tagihan', 'Tagihan & bayar', 'wallet', 'anak.tagihan'],
            ['anak.laporan', 'Lapor masalah', 'tool', 'anak.laporan'],
            ['anak.galon', 'Pesan galon', 'drop', 'anak.galon'],
            ['anak.pesan', 'Pesan ke pemilik', 'chat', 'anak.pesan'],
        ];
    @endphp
    <a class="skip-link" href="#konten">Ke konten utama</a>
    <div class="portal">
        <aside class="sidebar" id="menu-portal">
            <x-kos-brand />
            <p class="sidebar-label">{{ $owner ? 'Panel pemilik' : 'Ruang penghuni' }}</p>
            <nav aria-label="Menu dasbor">
                @foreach ($nav as [$route, $label, $icon, $pattern])
                    <a href="{{ route($route) }}" @if(request()->routeIs($pattern)) aria-current="page" @endif>
                        <x-kos-icon :name="$icon" /> {{ $label }}
                    </a>
                @endforeach
            </nav>
            <nav aria-label="Pengaturan akun" style="margin-top:12px">
                <a href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.edit')) aria-current="page" @endif><x-kos-icon name="users" /> Akun saya</a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit"><x-kos-icon name="out" /> Keluar</button>
            </form>
        </aside>
        <button type="button" class="drawer-backdrop" aria-label="Tutup menu" data-close-menu tabindex="-1"></button>
        <div class="portal-main">
            <header class="topbar">
                <button class="burger btn ghost sm" type="button" aria-controls="menu-portal" aria-expanded="false"><x-kos-icon name="menu" /> Menu</button>
                <span class="topbar-label">{{ $owner ? 'Panel pemilik' : 'Ruang penghuni' }}</span>
                <a class="text-link site-link" href="{{ route('home') }}">Lihat situs <x-kos-icon name="external" :width="15" :height="15" /></a>
                <span class="portal-avatar" title="{{ auth()->user()->name }}">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            </header>
            <main class="portal-content" id="konten">
                <x-kos-flash />
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
