@extends('layouts.kos')
@section('title', 'Form Kamar - KosKita')
@section('content')
<h1>{{ $room->exists ? 'Ubah ' . $room->label : 'Tambah kamar' }}</h1>
<form class="card" style="max-width:560px;margin-top:16px" method="POST" action="{{ $room->exists ? route('kamar.update', $room) : route('kamar.store') }}">
@csrf @if ($room->exists) @method('PUT') @endif
<label>Rumah kos</label>
<select name="house_id" required>
@foreach ($houses as $h)<option value="{{ $h->id }}" @selected(old('house_id', $room->house_id)==$h->id)>{{ $h->nama }} ({{ $h->jenis === 'putri' ? 'Putri' : 'Putra' }})</option>@endforeach
</select>
<label>Nomor kamar</label><input name="nomor" value="{{ old('nomor', $room->nomor) }}" required>
<label>Tipe (misalnya Kipas atau AC)</label><input name="tipe" value="{{ old('tipe', $room->tipe) }}" required>
<label>Harga per bulan (Rp)</label><input type="number" name="harga" value="{{ old('harga', $room->harga) }}" min="0" required>
<label>Fasilitas</label><textarea name="fasilitas" rows="3">{{ old('fasilitas', $room->fasilitas) }}</textarea>
<label>Status</label>
<select name="status">@foreach (['kosong','terisi','perawatan'] as $s)<option value="{{ $s }}" @selected(old('status', $room->status)===$s)>{{ ucfirst($s) }}</option>@endforeach</select>
<p style="margin-top:18px"><button class="btn">Simpan</button> <a class="btn ghost" href="{{ route('kamar.index') }}">Batal</a></p>
</form>
@endsection
