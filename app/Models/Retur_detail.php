<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur_detail extends Model
{
    use HasFactory;

    protected $table = 'retur_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];



   function penjualan_detail()
    {
     return $this->belongsTo(PenjualanDetail::class, 'penjualan_detail_id', 'id');
 
    }
   function produk()
    {
     return $this->belongsTo(Produk::class, 'Produk_id', 'id');
 
    }
}
