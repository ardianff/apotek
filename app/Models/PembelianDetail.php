<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'pembelian_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(
            Produk::class,
            'produk_id', // Foreign key on the owners table...
            'id', // Local key on the mechanics table...
         
        );
    }
    public function lokasi()
    {
        return $this->belongsTo(
            Lokasi::class,
            'lokasi_id', // Foreign key on the owners table...
            'id', // Local key on the mechanics table...
         
        );
    }

    public function pembelian()
    {
        return $this->hasOne(
            Pembelian::class,'id','pembelian_id'
        );
    }
}
