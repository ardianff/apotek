<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id',  'supplier_id');
    }
    public function pembelian_detail()
    {
        return $this->hasMany(PembelianDetail::class,  'pembelian_id','id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
