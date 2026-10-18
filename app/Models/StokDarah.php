
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDarah extends Model
{
    use HasFactory;

    protected $table = 'stok_darah';

    protected $fillable = [
        'golongan_darah',
        'rhesus',
        'jumlah',
        'min_stock',
    ];

    protected $casts = [
        'jumlah'    => 'integer',
        'min_stock' => 'integer',
    ];

    public function histories()
    {
        return $this->hasMany(StokDarahHistory::class, 'stok_id')->orderBy('created_at', 'desc');
    }

    public function getLabelAttribute(): string
    {
        return "{$this->golongan_darah}{$this->rhesus}";
    }

    public function isLowStock(): bool
    {
        return $this->jumlah <= $this->min_stock;
    }
}
