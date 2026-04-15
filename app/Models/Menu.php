<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';

    protected $fillable = [
        'nama_menu',
        'harga',
        'path_gambar',
        'idvendor',
    ];

    protected $casts = [
        'harga' => 'integer',
        'idvendor' => 'integer',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'id_vendor');
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu', 'idmenu');
    }
}
