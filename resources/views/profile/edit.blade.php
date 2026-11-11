@extends('layouts.kos')
@section('title', 'Akun saya · KosKita')
@section('content')
<h1>Akun saya</h1>
<p class="mu">Perbarui email dan kata sandi untuk masuk.</p>
@php
    $status = ['profile-updated' => 'Profil disimpan.', 'password-updated' => 'Kata sandi diperbarui.'];
@endphp
@if (isset($status[session('status')]))<div class="flash" role="status">{{ $status[session('status')] }}</div>@endif
<div class="card" style="max-width:670px">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
        <label>Nama <input name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required></label>
        <button class="btn" type="submit">Simpan profil</button>
    </form>
</div>
<h2>Ubah kata sandi</h2>
<div class="card" style="max-width:670px">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <label>Kata sandi saat ini <input type="password" name="current_password" autocomplete="current-password" required></label>
        <label>Kata sandi baru <input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
        <label>Ulangi kata sandi baru <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
        <button class="btn" type="submit">Ubah kata sandi</button>
    </form>
</div>
@if ($user->role === 'penghuni' && ! $user->tenant()->exists())
    <h2>Hapus akun</h2>
    <div class="card" style="max-width:670px">
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Hapus akun ini?')">
            @csrf
            @method('DELETE')
            <label>Kata sandi saat ini <input type="password" name="password" autocomplete="current-password" required></label>
            <button class="btn red" type="submit">Hapus akun</button>
        </form>
    </div>
@endif
@endsection
