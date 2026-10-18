<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatDonasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_donasi';

    protected $fillable = [
        'pendonor_id',
        'golongan',
        'rhesus',
        'jumlah',
        'tekanan_darah',
        'detak_jantung',
        'hemoglobin',
        'suhu_tubuh',
        'kesehatan_umum',
        'catatan',
        'status',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer',
    ];

    protected $appends = [
        'nama_pendonor',
        'nik',
        'telepon',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'pekerjaan',
        'label',
    ];

    public function pendonor()
    {
        return $this->belongsTo(Pendonor::class, 'pendonor_id');
    }

    public function getNamaPendonorAttribute(): string
    {
        $nama = $this->pendonor?->nama ?? '-';
        $clean = preg_replace('/\s+updated(\s+updated)*/i', '', $nama);
        $clean = preg_replace('/\s+update(\s+update)*/i', '', $clean);
        return trim($clean) ?: $nama;
    }

    public function getNikAttribute(): string
    {
        return $this->pendonor?->nik ?? '-';
    }

    public function getTeleponAttribute(): string
    {
        return $this->pendonor?->telepon ?? '-';
    }

    public function getAlamatAttribute(): string
    {
        return $this->pendonor?->alamat ?? '-';
    }

    public function getJenisKelaminAttribute(): string
    {
        return $this->pendonor?->jenis_kelamin ?? '-';
    }

    public function getTanggalLahirAttribute(): string
    {
        $tl = $this->pendonor?->tanggal_lahir;
        return $tl ? (is_string($tl) ? $tl : $tl->format('Y-m-d')) : '-';
    }

    public function getPekerjaanAttribute(): string
    {
        return $this->pendonor?->pekerjaan ?? '-';
    }

    public function getLabelAttribute(): string
    {
        return "{$this->golongan}{$this->rhesus}";
    }
}
