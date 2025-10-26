<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'retur_penjualan';
    protected $primaryKey = 'id';
    protected $guarded = [];



   function penjualan()
    {
     return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id');
 
    }
}
