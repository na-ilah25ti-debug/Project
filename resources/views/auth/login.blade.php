@extends('layouts.publik')
@section('title', 'Masuk penghuni · KosKita')
@section('content')
<div class="wrap auth-main">
    <div class="auth-grid">
        <aside>
            <img src="{{ asset('koskita/hero.webp') }}" alt="Ilustrasi halaman rumah kos">
            <p>Ilustrasi suasana kos</p>
        </aside>
        <div class="auth-panel">
            <p class="eyebrow">Ruang penghuni</p>
            <h1>Masuk akun</h1>
            <p>Akses tagihan, laporan, dan pesanan galonmu.</p>
            @if (session('status'))<p class="flash" role="status">{{ session('status') }}</p>@endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label>Email <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
                <label>Kata sandi <input type="password" name="password" autocomplete="current-password" required></label>
                <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button type="submit" class="btn">Masuk <x-kos-icon name="arrow" :width="17" :height="17" /></button>
            </form>
            <p class="auth-links"><a href="{{ route('register') }}">Daftar akun penghuni</a><a href="{{ route('password.request') }}">Lupa kata sandi?</a></p>
        </div>
    </div>
</div>
@endsection
