<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailObat extends Model
{
    use HasFactory;

    protected $table = 'detail_obat';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function obat()
    {
        return $this->belongsTo(Produk::class,  'produk_id','id');
    }
    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id',  'supplier_id');
    }
    public function lokasi()
    {
        return $this->hasOne(Lokasi::class,'id','lokasi_id');
    }

    public function pembelian()
    {
        return $this->hasOne(PembelianDetail::class,'id',  'beli_id');
    }
    public function stokOP()
    {
         return $this->hasOne(StokOpname::class,  'detail_obat_id','id');


    }
}
