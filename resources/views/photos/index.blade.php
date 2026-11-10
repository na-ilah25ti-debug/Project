@extends('layouts.kos')
@section('title', 'Foto - KosKita')
@section('content')
<h1>Foto</h1>
<p class="mu">Unggah foto yang tampil di halaman depan: banner, foto rumah kos, fasilitas, dan kamar. Maksimal 4 MB per foto.</p>

<form class="card" method="POST" action="{{ route('foto.store') }}" enctype="multipart/form-data">
@csrf
<div class="row">
<div><label>Jenis foto</label>
<select name="jenis" required>
<option value="banner" @selected(old('jenis')==='banner')>Banner halaman depan</option>
<option value="rumah" @selected(old('jenis')==='rumah')>Foto rumah kos (sampul)</option>
<option value="fasilitas" @selected(old('jenis')==='fasilitas')>Foto fasilitas</option>
<option value="kamar" @selected(old('jenis')==='kamar')>Foto kamar</option>
</select></div>
<div><label>Rumah kos (untuk sampul / fasilitas)</label>
<select name="house_id"><option value="">-</option>@foreach ($houses as $h)<option value="{{ $h->id }}" @selected(old('house_id')==$h->id)>{{ $h->nama }}</option>@endforeach</select></div>
<div><label>Kamar (untuk foto kamar)</label>
<select name="room_id"><option value="">-</option>@foreach ($rooms as $r)<option value="{{ $r->id }}" @selected(old('room_id')==$r->id)>{{ $r->label }}</option>@endforeach</select></div>
</div>
<label>Keterangan (opsional)</label><input name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Dapur bersama">
<label>Pilih foto (boleh lebih dari satu)</label><input type="file" name="foto[]" accept="image/*" multiple required>
<p style="margin-top:14px"><button class="btn">Unggah</button></p>
</form>

<h2>Foto yang sudah diunggah</h2>
<div class="rooms">
@forelse ($photos as $p)
<div class="card">
<img class="photo" src="{{ $p->url }}" alt="{{ $p->keterangan }}">
<span class="chip b-diproses">{{ ['banner'=>'Banner','rumah'=>'Rumah','fasilitas'=>'Fasilitas','kamar'=>'Kamar'][$p->jenis] }}</span>
<div class="mu" style="margin:6px 0">{{ $p->room?->label ?? $p->house?->nama ?? 'Halaman depan' }}@if ($p->keterangan) &middot; {{ $p->keterangan }}@endif</div>
<form method="POST" action="{{ route('foto.destroy', $p) }}" onsubmit="return confirm('Hapus foto ini?')">@csrf @method('DELETE')<button class="btn red sm">Hapus</button></form>
</div>
@empty
<p class="mu">Belum ada foto.</p>
@endforelse
</div>
@endsection
