<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galon_orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('jumlah');
            $t->unsignedInteger('harga_satuan');
            $t->string('catatan')->nullable();
            $t->enum('status', ['dipesan', 'diantar', 'selesai', 'batal'])->default('dipesan');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galon_orders');
    }
};
