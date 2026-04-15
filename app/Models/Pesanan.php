<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'timestamp',
        'total',
        'metode_bayar',
        'status_bayar',
        'payment_reference',
        'payment_channel',
        'paid_at',
    ];

    protected $casts = [
        'total' => 'integer',
        'metode_bayar' => 'integer',
        'status_bayar' => 'integer',
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    /**
     * Label metode bayar
     */
    public function getMetodeBayarLabelAttribute(): string
    {
        return match ($this->metode_bayar) {
            1 => 'Virtual Account',
            2 => 'QRIS',
            default => '-',
        };
    }

    /**
     * Label status bayar
     */
    public function getStatusBayarLabelAttribute(): string
    {
        return match ($this->status_bayar) {
            0 => 'Pending',
            1 => 'Lunas',
            2 => 'Gagal',
            default => '-',
        };
    }
}
