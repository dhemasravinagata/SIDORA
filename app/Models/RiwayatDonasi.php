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
        'label',
    ];

    public function pendonor()
    {
        return $this->belongsTo(Pendonor::class, 'pendonor_id');
    }

    public function getNamaPendonorAttribute(): string
    {
        return $this->pendonor?->nama ?? '-';
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

    public function getLabelAttribute(): string
    {
        return "{$this->golongan}{$this->rhesus}";
    }
}
