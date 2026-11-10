@extends('layouts.kos')
@section('title', 'Beranda - KosKita')
@section('content')
<h1>Halo, {{ auth()->user()->name }}</h1>
@if (! $t)
<div class="card" style="margin-top:16px">Akunmu belum terhubung ke kamar atau kamu sudah tidak aktif sebagai penghuni. Hubungi ibu kos lewat menu <a href="{{ route('anak.pesan') }}">Pesan</a>.</div>
@else
<p class="mu">{{ $t->room?->label }} &middot; masuk sejak {{ $t->tanggal_masuk->format('d/m/Y') }}</p>

<h2>Tagihan berikutnya</h2>
@if ($tagihan)
@php $sisa = (int) now()->startOfDay()->diffInDays($tagihan->jatuh_tempo, false); @endphp
<div class="card" style="max-width:520px">
<div class="mu">Periode {{ $tagihan->periode }}</div>
<div style="font-size:26px;font-weight:800">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</div>
<p style="margin:6px 0">Jatuh tempo: <b>{{ $tagihan->jatuh_tempo->format('d/m/Y') }}</b><br>
@if ($tagihan->status === 'menunggu') <span class="chip b-menunggu">Menunggu verifikasi ibu kos</span>
@elseif ($sisa < 0) <span class="chip b-telat">Terlambat {{ abs($sisa) }} hari</span>
@elseif ($sisa === 0) <span class="chip b-belum">Jatuh tempo hari ini</span>
@else <span class="chip b-belum">{{ $sisa }} hari lagi</span>@endif
</p>
@if ($tagihan->status === 'belum')<a class="btn" href="{{ route('anak.tagihan') }}">Bayar sekarang</a>@endif
</div>
@else
<div class="card" style="max-width:520px">Tidak ada tagihan yang menunggu pembayaran.</div>
@endif

<h2>Menu cepat</h2>
<div class="row">
<a class="card" style="text-decoration:none;color:inherit" href="{{ route('anak.galon') }}"><b>Pesan galon</b><div class="mu">Antar ke kamar</div></a>
<a class="card" style="text-decoration:none;color:inherit" href="{{ route('anak.laporan') }}"><b>Lapor masalah</b><div class="mu">Keran bocor, AC mati, dll.</div></a>
<a class="card" style="text-decoration:none;color:inherit" href="{{ route('anak.pesan') }}"><b>Pesan ke ibu kos</b><div class="mu">Tanya atau minta bantuan</div></a>
</div>
@endif
@endsection
