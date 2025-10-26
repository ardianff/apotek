<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kartu_stock extends Model
{
    use HasFactory;

    protected $table = 'kartu_stock';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(
            Produk::class,'id','produk_id'
          
        );
    // ->withPivot(['jumlah','tahun_id'])
    // ->withTimestamps();
    }
    
    public function user()
    {
        return $this->belongsTo(
            User::class,'user_id','id'
            
        );
    }
}