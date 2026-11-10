@extends('layouts.kos')
@section('title', 'Penghuni Masuk - KosKita')
@section('content')
<h1>Penghuni masuk</h1>
<form class="card" style="max-width:560px;margin-top:16px" method="POST" action="{{ route('penghuni.store') }}">
@csrf
<label>Kamar (hanya yang kosong)</label>
<select name="room_id" required>
<option value="">Pilih kamar</option>
@foreach ($rooms as $r)<option value="{{ $r->id }}" @selected(old('room_id')==$r->id)>{{ $r->label }} - {{ $r->tipe }} (Rp {{ number_format($r->harga, 0, ',', '.') }})</option>@endforeach
</select>
<label>Nama</label><input name="nama" value="{{ old('nama') }}" required>
<label>No. HP / WhatsApp</label><input name="no_hp" value="{{ old('no_hp') }}" required>
<label>Tanggal masuk</label><input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', now()->toDateString()) }}" required>
<label>Email login anak kos (opsional)</label><input type="email" name="email" value="{{ old('email') }}" placeholder="untuk masuk ke halaman anak kos">
<label>Password awal (wajib jika email diisi)</label><input type="text" name="password" value="{{ old('password') }}" placeholder="minimal 6 karakter">
<p style="margin-top:18px"><button class="btn">Simpan</button> <a class="btn ghost" href="{{ route('penghuni.index') }}">Batal</a></p>
</form>
@endsection
