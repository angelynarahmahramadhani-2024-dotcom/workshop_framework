<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'iddetail_pesanan';
    public $timestamps = false;

    protected $fillable = [
        'idmenu',
        'idpesanan',
        'jumlah',
        'harga',
        'subtotal',
        'timestamp',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'integer',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }
}
