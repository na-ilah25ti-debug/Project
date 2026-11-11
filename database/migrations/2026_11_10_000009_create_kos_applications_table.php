<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kos_applications', function (Blueprint $t) {
            $t->id();
            $t->string('nama_pengelola');
            $t->string('no_hp');
            $t->string('email')->nullable();
            $t->string('nama_kos');
            $t->text('alamat');
            $t->enum('jenis', ['putri', 'putra', 'campur']);
            $t->unsignedSmallInteger('jumlah_kamar');
            $t->string('bank');
            $t->string('no_rekening');
            $t->string('nama_rekening');
            $t->enum('status', ['baru', 'disetujui', 'ditolak'])->default('baru');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos_applications');
    }
};
