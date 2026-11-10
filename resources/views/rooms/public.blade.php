@extends('layouts.kos')
@section('title', 'Daftar Kamar - KosKita')
@section('content')
@php
    $wa = config('kos.whatsapp');
    $waLink = fn (string $teks) => 'https://wa.me/' . $wa . '?text=' . rawurlencode($teks);
@endphp

@if ($banner)<img class="hero" src="{{ $banner->url }}" alt="{{ $banner->keterangan ?: 'KosKita' }}">@endif

<div class="row" style="align-items:center">
<div><h1>Daftar kamar</h1><p class="mu" style="margin:0">Lihat kamar yang tersedia, harga, dan fasilitasnya tanpa perlu bertanya satu per satu lewat WhatsApp.</p></div>
<div style="flex:0"><a class="btn wa" style="white-space:nowrap" target="_blank" rel="noopener" href="{{ $waLink('Halo ' . config('kos.nama_ibu') . ', saya mau tanya soal kamar kos.') }}">Chat WhatsApp</a></div>
</div>

<form method="GET" class="card row" style="margin-top:16px">
<div><label>Rumah kos</label><select name="rumah"><option value="">Semua</option>@foreach ($semuaRumah as $h)<option value="{{ $h->id }}" @selected(request('rumah')==$h->id)>{{ $h->nama }}</option>@endforeach</select></div>
<div><label>Untuk</label><select name="jenis"><option value="">Semua</option><option value="putri" @selected(request('jenis')==='putri')>Putri</option><option value="putra" @selected(request('jenis')==='putra')>Putra</option></select></div>
<div><label>Status</label><select name="status"><option value="">Semua</option>@foreach (['kosong','terisi','perawatan'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>
<div><label>Tipe</label><select name="tipe"><option value="">Semua</option>@foreach ($tipe as $t)<option @selected(request('tipe')===$t)>{{ $t }}</option>@endforeach</select></div>
<div><label>Harga maksimal (Rp)</label><input type="number" name="maks" value="{{ request('maks') }}" min="0" step="50000"></div>
<div style="flex:0"><button class="btn">Cari</button></div>
</form>

@forelse ($houses as $h)
@php
    $cover = $h->photos->where('jenis', 'rumah')->first();
    $fasilitas = $h->photos->where('jenis', 'fasilitas');
@endphp
<section class="house">
@if ($cover)<img class="hero" style="max-height:220px" src="{{ $cover->url }}" alt="{{ $h->nama }}">@endif
<h2>{{ $h->nama }} <span class="chip b-{{ $h->jenis }}">{{ $h->jenis === 'putri' ? 'Putri' : 'Putra' }}</span></h2>
<p class="mu" style="margin:0">{{ $h->kosong_kamar }} dari {{ $h->total_kamar }} kamar kosong &middot; Fasilitas lainnya: {{ $h->fasilitas_umum ?: '-' }}</p>

@if ($fasilitas->isNotEmpty())
<div class="gallery">
@foreach ($fasilitas as $f)<a href="{{ $f->url }}" target="_blank" rel="noopener"><img src="{{ $f->url }}" alt="{{ $f->keterangan ?: 'Fasilitas ' . $h->nama }}" title="{{ $f->keterangan }}"></a>@endforeach
</div>
@endif

<div class="rooms">
@foreach ($h->rooms as $room)
@php $foto = $room->photos->first(); @endphp
<div class="card room">
@if ($foto)<a href="{{ $foto->url }}" target="_blank" rel="noopener"><img class="photo" src="{{ $foto->url }}" alt="Kamar {{ $room->nomor }}"></a>@endif
<h3>Kamar {{ $room->nomor }} <span class="chip b-{{ $room->status }}">{{ ucfirst($room->status) }}</span></h3>
<div class="mu">{{ $room->tipe }}</div>
<b>Rp {{ number_format($room->harga, 0, ',', '.') }}</b><span class="mu"> / bulan</span>
<p class="mu" style="margin:8px 0 0">{{ $room->fasilitas ?: 'Fasilitas belum diisi.' }}</p>
@if ($room->status === 'kosong')
<p style="margin:10px 0 0"><a class="btn wa sm" target="_blank" rel="noopener" href="{{ $waLink('Halo, saya mau tanya kamar ' . $room->nomor . ' di ' . $h->nama . ' (Rp ' . number_format($room->harga, 0, ',', '.') . '/bulan).') }}">Tanya kamar ini</a></p>
@endif
</div>
@endforeach
</div>
</section>
@empty
<p class="mu" style="margin-top:24px">Tidak ada kamar yang cocok dengan filter.</p>
@endforelse

<section class="house" id="pesan">
<h2>Kirim pesan ke {{ config('kos.nama_ibu') }}</h2>
<p class="mu" style="margin:0 0 10px">Tanya kamar, harga, atau jadwal survei. Balasan dikirim lewat kontak yang kamu isi, atau langsung lewat WhatsApp.</p>
<form class="card" method="POST" action="{{ route('pesan.kirim') }}" style="max-width:640px">
@csrf
<div class="row">
<div><label>Nama</label><input name="nama" value="{{ old('nama', auth()->user()?->name) }}" required></div>
<div><label>No. HP / WhatsApp</label><input name="kontak" value="{{ old('kontak') }}" placeholder="08xxxxxxxxxx"></div>
</div>
<label>Pesan</label><textarea name="isi" rows="3" required>{{ old('isi') }}</textarea>
<p style="margin-top:14px"><button class="btn">Kirim pesan</button>
<a class="btn wa" target="_blank" rel="noopener" href="{{ $waLink('Halo ' . config('kos.nama_ibu') . ', saya mau tanya soal kos.') }}">Atau lewat WhatsApp</a></p>
</form>
@if ($pesanSaya->isNotEmpty())
<h3 style="margin-top:22px">Pesanmu &amp; balasan</h3>
@foreach ($pesanSaya as $m)
<div class="card" style="max-width:640px;margin-bottom:10px">
<div class="mu">{{ $m->created_at->format('d/m/Y H:i') }}</div>
<p style="margin:4px 0">{{ $m->isi }}</p>
@if ($m->balasan)<div class="bubble"><b>{{ config('kos.nama_ibu') }}</b> <span class="mu">({{ $m->balasan_at?->format('d/m/Y H:i') }})</span><br>{{ $m->balasan }}</div>
@else<span class="chip b-menunggu">Belum dibalas</span>@endif
</div>
@endforeach
@endif
</section>
@endsection
