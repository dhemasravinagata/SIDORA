<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDonor extends Model
{
    use HasFactory;

    protected $table = 'jadwal_donor';

    protected $fillable = [
        'lokasi',
        'tanggal',
        'target',
        'terdaftar',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'target'    => 'integer',
        'terdaftar' => 'integer',
    ];
}
