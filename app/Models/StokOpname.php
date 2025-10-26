<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stokopname';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(
            Produk::class, 'produk_id','id'
        );
    }
    public function obat()
    {
        return $this->belongsTo(
            DetailObat::class, 'detail_obat_id','id'
        );
    }
    public function lokasi()
    {
        return $this->belongsTo(
            Lokasi::class,'id', 'lokasi_id'
        );
    }
}
