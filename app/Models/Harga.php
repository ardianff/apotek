<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class harga extends Model
{
    use HasFactory;
    protected $table = 'harga';
    protected $primaryKey = 'id';
    protected $guarded = [];

   
        public function produk()
        {
            return $this->hasMany(Produk::class, 'obat_id', 'id');
        }
    
}
