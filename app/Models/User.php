<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'telepon',
        'kontak',
        'alamat',
        'kecamatan',
        'desa',
        'kode_pos',
        'provinsi',
        'tipe_rs',
        'no_izin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isRumahSakit(): bool
    {
        return $this->role === 'rumahsakit';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function permintaanDarah()
    {
        return $this->hasMany(PermintaanDarah::class, 'user_id');
    }

    public function stokHistory()
    {
        return $this->hasMany(StokDarahHistory::class, 'user_id');
    }
}
