@extends('layouts.kos')
@section('title', 'Pesan - KosKita')
@section('content')
<h1>Pesan masuk</h1>
<p class="mu">Pesan dari pengunjung dan anak kos. Balas di sini, atau lanjutkan lewat WhatsApp.</p>
@forelse ($messages as $m)
@php
    $n = preg_replace('/\D/', '', $m->kontak ?? '');
    if (str_starts_with($n, '0')) { $n = '62' . substr($n, 1); }
@endphp
<div class="card" style="margin-bottom:12px">
<div><b>{{ $m->nama }}</b> <span class="mu">&middot; {{ $m->kontak ?: 'tanpa kontak' }} &middot; {{ $m->created_at->format('d/m/Y H:i') }}</span>
@if (! $m->dibaca) <span class="chip b-dipesan">Baru</span>@endif
@if ($m->user_id) <span class="chip b-putra">Anak kos</span>@endif</div>
<p style="margin:8px 0">{{ $m->isi }}</p>
@if ($m->balasan)<div class="bubble"><b>Balasanmu</b> <span class="mu">({{ $m->balasan_at?->format('d/m/Y H:i') }})</span><br>{{ $m->balasan }}</div>@endif
<form method="POST" action="{{ route('pesan.balas', $m) }}" style="margin-top:10px">@csrf @method('PATCH')
<textarea name="balasan" rows="2" placeholder="Tulis balasan...">{{ $m->balasan }}</textarea>
<p style="margin:8px 0 0"><button class="btn sm">Simpan balasan</button>
@if ($n)<a class="btn wa sm" target="_blank" rel="noopener" href="https://wa.me/{{ $n }}">Buka WhatsApp</a>@endif
</p></form>
<div style="margin-top:8px">
@if (! $m->dibaca)<form class="inline" method="POST" action="{{ route('pesan.baca', $m) }}">@csrf @method('PATCH')<button class="btn ghost sm">Tandai dibaca</button></form>@endif
<form class="inline" method="POST" action="{{ route('pesan.destroy', $m) }}" onsubmit="return confirm('Hapus pesan ini?')">@csrf @method('DELETE')<button class="btn ghost sm">Hapus</button></form></div>
</div>
@empty
<p class="mu">Belum ada pesan.</p>
@endforelse
@endsection
