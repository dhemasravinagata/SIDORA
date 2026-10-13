<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_donor', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi', 255);
            $table->date('tanggal');
            $table->integer('target')->default(0);
            $table->integer('terdaftar')->default(0);
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('status', 50)->default('Akan Datang');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_donor');
    }
};
