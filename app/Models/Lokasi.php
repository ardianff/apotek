<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $primaryKey = 'id';
    protected $guarded = [];


     public function pembelian()
    {
        return $this->hasMany(
            PembelianDetail::class,'id','lokasi_id'
        );
    }
}
