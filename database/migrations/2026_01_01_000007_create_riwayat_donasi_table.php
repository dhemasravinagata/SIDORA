<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendonor_id')->constrained('pendonor')->cascadeOnDelete();
            $table->string('golongan', 10);
            $table->string('rhesus', 5);
            $table->integer('jumlah')->default(1);
            $table->string('tekanan_darah', 20)->default('120/80');
            $table->enum('status', ['Berhasil', 'Ditolak'])->default('Berhasil');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_donasi');
    }
};
