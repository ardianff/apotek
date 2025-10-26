<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur_pembelian extends Model
{
    use HasFactory;

    protected $table = 'retur_pembelian';
    protected $primaryKey = 'id';
    protected $guarded = [];




    
   function returdetail()
   {
    return $this->hasMany(Retur_pembelian_detail::class, 'retur_pembelian_id', 'id');

   }

   function pembelian()
    {
     return $this->belongsTo(Pembelian::class, 'pembelian_id', 'id');
 
    }
   function supplier()
    {
     return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
 
    }
   function pembelian_detail()
    {
     return $this->belongsTo(pembelian_detail::class, 'pembelian_detail_id', 'id');
 
    }
   
}
