<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_darah_history', function (Blueprint $table) {
            $table->id();
            $table->string('golongan', 10);
            $table->string('rhesus', 5);
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_darah_history');
    }
};

