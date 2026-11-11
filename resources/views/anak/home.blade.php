@extends('layouts.kos')
@section('title', 'Ruang penghuni · KosKita')
@section('content')
<div class="tenant-hero">
    <div class="card">
        <p class="eyebrow">Ruang penghuni</p>
        <h1>Halo, {{ auth()->user()->name }}.</h1>
        @if ($t)
            <p class="mu">{{ $t->room?->label }} · Masuk {{ $t->tanggal_masuk->format('d/m/Y') }}</p>
        @else
            <p class="mu">Akunmu belum terhubung ke kamar aktif. Kirim pesan ke pemilik agar kamar bisa dihubungkan ke email akunmu.</p>
        @endif
        <a class="text-link" href="{{ route('anak.pesan') }}"><x-kos-icon name="chat" /> Pesan ke pemilik</a>
    </div>
    <div class="card payment-preview">
        <p class="eyebrow">Tagihan berikutnya</p>
        @if ($tagihan)
            <p class="amount">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
            <p>Periode {{ $tagihan->periode }} · {{ $tagihan->jatuh_tempo->format('d/m/Y') }}</p>
            @if ($tagihan->status === 'menunggu')
                <span class="chip b-menunggu">Menunggu verifikasi</span>
            @elseif ($tagihan->terlambat)
                <span class="chip b-telat">Lewat jatuh tempo</span>
            @endif
            <a class="btn sm" href="{{ route('anak.tagihan') }}">Lihat tagihan <x-kos-icon name="arrow" :width="16" :height="16" /></a>
        @else
            <p>{{ $t ? 'Tidak ada tagihan yang menunggu pembayaran.' : 'Tagihan muncul setelah kamar dihubungkan.' }}</p>
        @endif
    </div>
</div>
<div class="quick-actions">
    @foreach ([
        ['anak.tagihan', 'Tagihan & bayar', 'wallet', $stat['tagihan'] . ' tagihan belum dibayar'],
        ['anak.laporan', 'Lapor masalah', 'tool', $stat['laporan'] . ' laporan terbuka'],
        ['anak.galon', 'Pesan galon', 'drop', $stat['galon'] . ' pesanan aktif'],
        ['anak.pesan', 'Pesan ke pemilik', 'chat', $stat['pesan'] . ' pesan menunggu balasan'],
    ] as [$route, $label, $icon, $note])
        <a class="card quick-action" href="{{ route($route) }}">
            <x-kos-icon :name="$icon" /><strong>{{ $label }}</strong><small>{{ $note }}</small>
        </a>
    @endforeach
</div>
<h2>Aktivitas terbaru</h2>
<div class="card">
    <ul class="activity-list">
        @forelse ($notif as $item)
            <li><div><strong>{{ $item['judul'] }}</strong>@if($item['isi'])<p class="mu">{{ $item['isi'] }}</p>@endif</div><small>{{ $item['waktu']?->format('d/m H:i') }}</small></li>
        @empty
            <li class="mu">Belum ada aktivitas.</li>
        @endforelse
    </ul>
</div>
@endsection
