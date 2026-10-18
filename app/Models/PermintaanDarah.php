<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanDarah extends Model
{
    use HasFactory;

    protected $table = 'permintaan_darah';

    protected $fillable = [
        'user_id',
        'status',
        'prioritas',
        'keterangan',
        'alasan_tolak',
        'tanggal_tolak',
        'kurir',
        'tanggal_kirim',
        'tanggal_terima',
        'penerima',
    ];

    protected $casts = [
        'tanggal_tolak'  => 'date',
        'tanggal_kirim'  => 'date',
        'tanggal_terima' => 'date',
    ];

    protected $appends = [
        'rumah_sakit',
        'golongan',
        'rhesus',
        'detail_jumlah',
        'tanggal',
        'total_kantong',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanDarahDetail::class, 'permintaan_id');
    }

    public function getRumahSakitAttribute(): string
    {
        return $this->user?->name ?? '-';
    }

    public function getGolonganAttribute(): string
    {
        return $this->details->first()?->golongan ?? '-';
    }

    public function getRhesusAttribute(): string
    {
        return $this->details->first()?->rhesus ?? '';
    }

    public function getDetailJumlahAttribute(): int
    {
        return (int) ($this->details->sum('jumlah') ?: 1);
    }

    public function getTanggalAttribute(): string
    {
        return $this->created_at?->format('Y-m-d') ?? date('Y-m-d');
    }

    public function getTotalKantongAttribute(): int
    {
        return (int) $this->details->sum('jumlah');
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'Disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'Ditolak';
    }

    public function isDikirim(): bool
    {
        return $this->status === 'Dikirim';
    }

    public function isDiterima(): bool
    {
        return $this->status === 'Diterima';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'Selesai' || $this->status === 'Diterima';
    }
}
