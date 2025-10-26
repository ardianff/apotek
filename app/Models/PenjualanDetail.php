<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function prodk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function obat()
    {
        return $this->belongsTo(DetailObat::class, 'obat_id', 'id');
    }
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id', 'penjualan_id');
    }

    
}
