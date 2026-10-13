<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'petugas', 'rumahsakit'])->default('petugas');
            $table->enum('status', ['pending', 'aktif', 'ditolak', 'nonaktif'])->default('pending');
            $table->string('telepon', 50)->nullable();
            $table->string('kontak', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('desa', 100)->nullable();
            $table->string('kode_pos', 20)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('tipe_rs', 100)->nullable();
            $table->string('no_izin', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
