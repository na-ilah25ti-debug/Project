@extends('layouts.kos')
@section('title', 'Tambah penghuni · KosKita')
@section('content')
<h1>Tambah penghuni</h1>
<p class="mu">Hubungkan penghuni ke salah satu kamar yang tersedia.</p>
<form class="card" method="POST" action="{{ route('penghuni.store') }}" style="max-width:720px">
    @csrf
    <label>Kamar
        <select name="room_id" required>
            <option value="">Pilih kamar</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->label }}</option>
            @endforeach
        </select>
    </label>
    <div class="row">
        <label>Nama penghuni <input name="nama" value="{{ old('nama') }}" maxlength="100" required></label>
        <label>No. WhatsApp <input name="no_hp" type="tel" value="{{ old('no_hp') }}" maxlength="20" required></label>
    </div>
    <label>Tanggal masuk <input name="tanggal_masuk" type="date" value="{{ old('tanggal_masuk', now()->toDateString()) }}" required></label>
    <h2>Akun penghuni</h2>
    <p class="mu">Masukkan email yang sudah didaftarkan penghuni. Untuk membuat akun baru, isi email dan kata sandi sementara. Kosongkan keduanya jika penghuni belum memerlukan akun.</p>
    <label>Email <input name="email" type="email" value="{{ old('email') }}" maxlength="255"></label>
    <label>Kata sandi sementara untuk akun baru <input name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72"></label>
    <div class="actions" style="margin-top:16px">
        <button class="btn" type="submit">Simpan penghuni</button>
        <a class="btn ghost" href="{{ route('penghuni.index') }}">Batal</a>
    </div>
</form>
@endsection
