<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = [
        'nama',
        'alamat',
        'kode_provinsi',
        'kode_kota',
        'kode_kecamatan',
        'kode_kelurahan',
        'foto_blob',
        'foto_path',
    ];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'kode_provinsi', 'kode');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kode_kota', 'kode');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kode_kecamatan', 'kode');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kode_kelurahan', 'kode');
    }
}
