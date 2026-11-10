<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $t) {
            $t->id();
            $t->foreignId('house_id')->constrained()->cascadeOnDelete();
            $t->string('nomor');
            $t->string('tipe')->default('Kipas');
            $t->unsignedInteger('harga');
            $t->text('fasilitas')->nullable();
            $t->enum('status', ['kosong', 'terisi', 'perawatan'])->default('kosong');
            $t->timestamps();
            $t->unique(['house_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
