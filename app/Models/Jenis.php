<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $table = 'jenis';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'jenis_id', 'id');
    }
}
