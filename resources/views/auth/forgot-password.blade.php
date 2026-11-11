@extends('layouts.publik')
@section('title', 'Lupa kata sandi · KosKita')
@section('content')
<div class="wrap auth-main auth-owner">
    <div class="auth-grid"><div class="auth-panel">
        <p class="eyebrow">Ruang penghuni</p>
        <h1>Lupa kata sandi?</h1>
        <p>Masukkan email akunmu untuk menerima tautan pengaturan ulang kata sandi.</p>
        @if (session('status'))<p class="flash" role="status">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label>Email <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
            <button class="btn" type="submit">Kirim tautan</button>
        </form>
        <p class="auth-links"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
    </div></div>
</div>
@endsection
