<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDarahDetail extends Model
{
    use HasFactory;

    protected $table = 'permintaan_darah_detail';

    protected $fillable = [
        'permintaan_id',
        'golongan',
        'rhesus',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanDarah::class, 'permintaan_id');
    }

    public function getLabelAttribute(): string
    {
        return "{$this->golongan}{$this->rhesus}";
    }
}
