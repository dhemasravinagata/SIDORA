<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendonor extends Model
{
    use HasFactory;

    protected $table = 'pendonor';

    protected $fillable = [
        'nama',
        'nik',
        'telepon',
        'email',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'kota',
        'provinsi',
        'pekerjaan',
        'golongan',
        'rhesus',
        'status',
        'tgl_donor',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_donor'     => 'date',
    ];

    public function riwayatDonasi()
    {
        return $this->hasMany(RiwayatDonasi::class, 'pendonor_id');
    }
}
