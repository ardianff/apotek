<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];



   function retur()
    {
     return $this->belongsTo(Retur::class, 'retur_id', 'id');
 
    }
   function produk()
    {
     return $this->belongsTo(Produk::class, 'produk_id', 'id');
 
    }
   function obat()
    {
     return $this->belongsTo(DetailObat::class, 'obat_id', 'id');
 
    }
}
