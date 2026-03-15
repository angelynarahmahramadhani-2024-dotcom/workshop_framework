<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kode', 'kode_kota', 'nama'];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kode_kota', 'kode');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'kode_kecamatan', 'kode');
    }
}
