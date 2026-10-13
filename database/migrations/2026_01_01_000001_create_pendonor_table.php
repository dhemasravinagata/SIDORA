<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendonor', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 191);
            $table->string('nik', 50)->unique();
            $table->string('telepon', 50)->nullable();
            $table->string('email', 191)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('golongan', 10);
            $table->string('rhesus', 5);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tgl_donor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendonor');
    }
};
