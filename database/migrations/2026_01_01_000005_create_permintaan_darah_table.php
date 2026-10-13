<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_darah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak', 'Dikirim'])->default('Pending');
            $table->string('prioritas', 50)->default('Biasa');
            $table->text('keterangan')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->date('tanggal_tolak')->nullable();
            $table->string('kurir', 191)->nullable();
            $table->date('tanggal_kirim')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_darah');
    }
};
