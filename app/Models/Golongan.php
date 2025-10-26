<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $table = 'golongan';
    protected $primaryKey = 'id';
    protected $guarded = [];
    

    public function produk()
    {
        return $this->hasMany(Produk::class, 'golongan_id', 'id');
    }
}
