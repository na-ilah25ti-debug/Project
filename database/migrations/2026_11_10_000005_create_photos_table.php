<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('house_id')->nullable()->constrained()->cascadeOnDelete();
            $t->foreignId('room_id')->nullable()->constrained()->cascadeOnDelete();
            $t->enum('jenis', ['banner', 'rumah', 'fasilitas', 'kamar']);
            $t->string('path');
            $t->string('keterangan')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
