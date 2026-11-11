@extends('layouts.kos')
@section('title', 'Pesan - KosKita')
@section('content')
<h1>Pesan ke ibu kos</h1>
<p class="mu">Kirim pertanyaan atau permintaan (tambah lemari, pindah kamar, dll.). Atau langsung chat lewat WhatsApp.</p>
<form class="card" method="POST" action="{{ route('anak.pesan.store') }}" style="max-width:560px">
@csrf
<label>Pesan <textarea name="isi" rows="3" maxlength="1000" required>{{ old('isi') }}</textarea></label>
<p style="margin-top:14px"><button class="btn">Kirim</button>
@if (config('kos.whatsapp'))<a class="btn ghost" target="_blank" rel="noopener" href="https://wa.me/{{ config('kos.whatsapp') }}?text={{ rawurlencode('Halo, saya ' . auth()->user()->name . ', penghuni kos.') }}">WhatsApp</a>@endif</p>
</form>
<h2>Percakapan</h2>
@forelse ($messages as $m)
<div class="card" style="margin-bottom:10px">
<div class="mu">{{ $m->created_at->format('d/m/Y H:i') }}</div>
<p style="margin:4px 0">{{ $m->isi }}</p>
@if ($m->balasan)<div class="bubble"><b>{{ config('kos.nama_ibu') }}</b> <span class="mu">({{ $m->balasan_at?->format('d/m/Y H:i') }})</span><br>{{ $m->balasan }}</div>
@else<span class="chip b-menunggu">Belum dibalas</span>@endif
</div>
@empty
<p class="mu">Belum ada pesan.</p>
@endforelse
@endsection
