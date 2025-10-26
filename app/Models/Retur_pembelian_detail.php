<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur_pembelian_detail extends Model
{
    use HasFactory;

    protected $table = 'retur_pembelian_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    function produk()
    {
     return $this->belongsTo(Produk::class, 'produk_id', 'id');
 
    }
    function beli()
    {
     return $this->belongsTo(PembelianDetail::class, 'pembelian_detail_id', 'id');
 
    }
    function detailobat()
    {
     return $this->belongsTo(DetailObat::class, 'obat_id', 'id');
 
    }


}
