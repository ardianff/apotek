<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $table = 'cabang';
    protected $primaryKey = 'id';
    protected $guarded = [];

   
        public function produk()
        {
            return $this->hasMany(Produk::class, 'cabang_id', 'id');
        }
    
}
