<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_darah', function (Blueprint $table) {
            $table->id();
            $table->string('golongan_darah', 10);
            $table->string('rhesus', 5);
            $table->integer('jumlah')->default(0);
            $table->integer('min_stock')->default(10);
            $table->timestamps();

            $table->unique(['golongan_darah', 'rhesus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah');
    }
};
