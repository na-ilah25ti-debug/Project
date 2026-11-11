@extends('layouts.publik')
@section('title', 'Atur ulang kata sandi · KosKita')
@section('content')
<div class="wrap auth-main auth-owner">
    <div class="auth-grid"><div class="auth-panel">
        <p class="eyebrow">Ruang penghuni</p>
        <h1>Atur ulang kata sandi</h1>
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <label>Email <input type="email" name="email" value="{{ old('email', $request->email) }}" autocomplete="email" required autofocus></label>
            <label>Kata sandi baru <input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
            <label>Ulangi kata sandi <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
            <button class="btn" type="submit">Simpan kata sandi</button>
        </form>
    </div></div>
</div>
@endsection
