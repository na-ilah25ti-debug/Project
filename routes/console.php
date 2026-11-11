<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

Artisan::command('kos:pemilik {email}', function () {
    $email = Str::lower($this->argument('email'));
    $validator = Validator::make(['email' => $email], ['email' => 'required|email|max:255']);
    if ($validator->fails()) {
        $this->error('Email pemilik tidak valid.');

        return 1;
    }

    $user = User::where('email', $email)->first();
    if ($user && $user->role !== 'pemilik') {
        $this->error('Email itu sudah terdaftar sebagai penghuni. Gunakan email khusus pemilik.');

        return 1;
    }

    $password = $this->secret('Kata sandi baru (minimal 12 karakter)');
    $confirmation = $this->secret('Ulangi kata sandi');
    if (! is_string($password) || mb_strlen($password) < 12 || strlen($password) > 72 || $password !== $confirmation) {
        $this->error('Pastikan kedua isian sama, minimal 12 karakter dan maksimal 72 byte.');

        return 1;
    }

    $user ??= new User(['email' => $email, 'name' => 'Pemilik Kos', 'role' => 'pemilik']);
    $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();

    $this->info('Akun pemilik siap digunakan di /admin/masuk.');

    return 0;
})->purpose('Buat akun pemilik kos atau atur ulang kata sandinya');
