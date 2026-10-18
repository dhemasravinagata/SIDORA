<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaan_darah', function (Blueprint $table) {
            if (! Schema::hasColumn('permintaan_darah', 'tanggal_terima')) {
                $table->date('tanggal_terima')->nullable()->after('tanggal_kirim');
            }
            if (! Schema::hasColumn('permintaan_darah', 'penerima')) {
                $table->string('penerima', 191)->nullable()->after('tanggal_terima');
            }
        });

        // Update Postgres CHECK constraint if running on PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE permintaan_darah DROP CONSTRAINT IF EXISTS permintaan_darah_status_check");
            DB::statement("ALTER TABLE permintaan_darah ADD CONSTRAINT permintaan_darah_status_check CHECK (status IN ('Pending', 'Disetujui', 'Ditolak', 'Dikirim', 'Diterima'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE permintaan_darah DROP CONSTRAINT IF EXISTS permintaan_darah_status_check");
            DB::statement("ALTER TABLE permintaan_darah ADD CONSTRAINT permintaan_darah_status_check CHECK (status IN ('Pending', 'Disetujui', 'Ditolak', 'Dikirim'))");
        }

        Schema::table('permintaan_darah', function (Blueprint $table) {
            if (Schema::hasColumn('permintaan_darah', 'penerima')) {
                $table->dropColumn('penerima');
            }
            if (Schema::hasColumn('permintaan_darah', 'tanggal_terima')) {
                $table->dropColumn('tanggal_terima');
            }
        });
    }
};
