<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendonor', function (Blueprint $table) {
            $table->string('tekanan_darah', 20)->nullable()->default('120/80');
            $table->string('detak_jantung', 20)->nullable()->default('72');
            $table->string('hemoglobin', 20)->nullable()->default('14.5');
            $table->string('suhu_tubuh', 20)->nullable()->default('36.5');
            $table->string('kesehatan_umum', 50)->nullable()->default('Sehat Baik');
            $table->text('catatan')->nullable();
        });

        Schema::table('riwayat_donasi', function (Blueprint $table) {
            $table->string('detak_jantung', 20)->nullable()->default('72');
            $table->string('hemoglobin', 20)->nullable()->default('14.5');
            $table->string('suhu_tubuh', 20)->nullable()->default('36.5');
            $table->string('kesehatan_umum', 50)->nullable()->default('Sehat Baik');
            $table->text('catatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pendonor', function (Blueprint $table) {
            $table->dropColumn([
                'tekanan_darah',
                'detak_jantung',
                'hemoglobin',
                'suhu_tubuh',
                'kesehatan_umum',
                'catatan',
            ]);
        });

        Schema::table('riwayat_donasi', function (Blueprint $table) {
            $table->dropColumn([
                'detak_jantung',
                'hemoglobin',
                'suhu_tubuh',
                'kesehatan_umum',
                'catatan',
            ]);
        });
    }
};
