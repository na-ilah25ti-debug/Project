@extends('layouts.publik')
@section('title', 'Daftar penghuni · KosKita')
@section('content')
<div class="wrap auth-main">
    <div class="auth-grid">
        <aside>
            <img src="{{ asset('koskita/hero.webp') }}" alt="Ilustrasi halaman rumah kos">
            <p>Ilustrasi suasana kos</p>
        </aside>
        <div class="auth-panel">
            <p class="eyebrow">Ruang penghuni</p>
            <h1>Buat akun</h1>
            <p>Gunakan email yang akan diberikan ke pemilik untuk menghubungkan akun ke kamar.</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <label>Nama lengkap <input name="name" value="{{ old('name') }}" autocomplete="name" required autofocus></label>
                <label>Email <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
                <label>Kata sandi <input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
                <label>Ulangi kata sandi <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" required></label>
                <button type="submit" class="btn">Daftar <x-kos-icon name="arrow" :width="17" :height="17" /></button>
            </form>
            <p class="auth-links"><a href="{{ route('login') }}">Sudah punya akun? Masuk</a></p>
        </div>
    </div>
</div>
@endsection
