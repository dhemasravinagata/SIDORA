
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDarahHistory extends Model
{
    use HasFactory;

    protected $table = 'stok_darah_history';

    protected $fillable = [
        'golongan',
        'rhesus',
        'masuk',
        'keluar',
        'stok_akhir',
        'catatan',
    ];

    protected $casts = [
        'masuk'      => 'integer',
        'keluar'     => 'integer',
        'stok_akhir' => 'integer',
    ];
}
