<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = [
        'nama_vendor',
    ];

    public function menu()
    {
        return $this->hasMany(Barang::class, 'id_vendor', 'id_vendor');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_vendor', 'id_vendor');
    }
}
