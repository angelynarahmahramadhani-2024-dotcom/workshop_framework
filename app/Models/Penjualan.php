<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'user_id',
        'nama_customer',
        'id_vendor',
        'total',
        'metode_bayar',
        'payment_channel',
        'payment_reference',
        'status_bayar',
        'paid_at',
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }
}
