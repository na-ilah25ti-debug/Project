<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Masuk pemilik · KosKita</title>
    <link rel="stylesheet" href="{{ asset('koskita/site.css') }}">
</head>
<body class="auth-owner">
    <main class="wrap auth-main">
        <div class="auth-grid">
            <div class="auth-panel">
                <x-kos-brand />
                <h1>Masuk pemilik</h1>
                <x-kos-flash />
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <label>Email <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
                    <label>Kata sandi <input type="password" name="password" autocomplete="current-password" required></label>
                    <button class="btn" type="submit">Masuk <x-kos-icon name="arrow" :width="17" :height="17" /></button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
