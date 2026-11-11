@extends('layouts.publik')
@section('title', $room->label . ' · KosKita')
@section('content')
@php
    $fasilitas = collect(explode(',', (string) $room->fasilitas))->map(fn ($item) => trim($item))->filter();
    $fasilitasUmum = $room->house->photos->where('jenis', 'fasilitas');
    $harga = 'Rp ' . number_format($room->harga, 0, ',', '.');
    $pesan = 'Halo, saya ingin bertanya tentang ' . $room->label . '.';
    $whatsapp = config('kos.whatsapp');
    $waLink = $whatsapp ? 'https://wa.me/' . $whatsapp . '?text=' . rawurlencode($pesan) : null;
    $statusKamar = ['kosong' => 'Tersedia', 'terisi' => 'Terisi', 'perawatan' => 'Perawatan'];
@endphp
<div class="wrap detail-page">
    <a class="text-link back-link" href="{{ route('home', ['rumah' => $room->house_id]) }}#kamar">← Kembali ke daftar kamar</a>
    <div class="detail-heading">
        <div class="actions"><span class="pill">{{ $room->house->jenis === 'putri' ? 'Kos putri' : 'Kos putra' }}</span><span class="chip b-{{ $room->status }}">{{ $statusKamar[$room->status] }}</span></div>
        <h1>{{ $room->house->nama }} <em>· Kamar {{ $room->nomor }}</em></h1>
        <p class="mu">Tipe {{ $room->tipe }}</p>
    </div>
    @if ($room->photos->isNotEmpty())
        <div class="detail-gallery">
            @foreach ($room->photos as $foto)
                <a href="{{ $foto->url }}" target="_blank" rel="noopener"><img src="{{ $foto->url }}" alt="{{ $foto->keterangan ?: $room->label }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}"></a>
            @endforeach
        </div>
    @endif
    <div class="detail-grid">
        <div class="card details-card">
            <h2>Fasilitas kamar</h2>
            <div class="facilities">@forelse ($fasilitas as $item)<span>{{ $item }}</span>@empty <span>Belum dicantumkan</span> @endforelse</div>
            <h2>Fasilitas bersama</h2>
            <p class="mu">{{ $room->house->fasilitas_umum ?: 'Tanyakan fasilitas bersama ke pemilik.' }}</p>
            @if ($fasilitasUmum->isNotEmpty())
                <div class="detail-gallery small">
                    @foreach ($fasilitasUmum as $foto)
                        <a href="{{ $foto->url }}" target="_blank" rel="noopener"><img src="{{ $foto->url }}" alt="{{ $foto->keterangan ?: 'Fasilitas bersama' }}" loading="lazy"></a>
                    @endforeach
                </div>
            @endif
            @if ($room->photos->isEmpty())<p class="photo-note"><x-kos-icon name="photo" /> Foto kamar bisa diminta langsung ke pemilik.</p>@endif
        </div>
        <aside class="card inquiry">
            <p class="eyebrow">Harga sewa</p>
            <p class="detail-price">{{ $harga }} <small>/ bulan</small></p>
            @if ($room->status !== 'kosong')
                <p class="mu">Kamar sedang {{ $room->status }}. <a href="{{ route('home', ['status' => 'kosong']) }}#kamar">Cek kamar yang tersedia.</a></p>
            @endif
            @if ($waLink)<a class="btn ghost" href="{{ $waLink }}" target="_blank" rel="noopener"><x-kos-icon name="chat" /> Tanya via WhatsApp <x-kos-icon name="external" :width="16" :height="16" /></a>@endif
            <h2>Kirim pesan</h2>
            <x-kos-contact message="Halo, apakah saya bisa survei kamar ini?" :room="$room" />
        </aside>
    </div>
</div>
@endsection
