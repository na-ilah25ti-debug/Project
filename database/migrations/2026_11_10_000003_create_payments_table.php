<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('periode', 7); // format Y-m
            $t->unsignedInteger('jumlah');
            $t->date('jatuh_tempo');
            $t->date('tanggal_bayar')->nullable();
            $t->string('metode')->nullable();
            $t->string('bukti')->nullable(); // path di disk "local" (privat)
            $t->enum('status', ['belum', 'menunggu', 'lunas'])->default('belum');
            $t->timestamps();
            $t->unique(['tenant_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
