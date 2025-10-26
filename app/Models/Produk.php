<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function kategori()
    {
        return $this->hasOne(
            Kategori::class,'id', 'kategori_id'
        );
    }
    public function jenis()
    {
        return $this->hasOne(
            Jenis::class,'id', 'jenis_id'
        );
    }
    public function golongan()
    {
        return $this->hasOne(
            Golongan::class,'id', 'golongan_id'
        );
    }
    public function lokasi()
    {
        return $this->hasOne(
            Lokasi::class,'id','lokasi_id'
        );
    }
    public function cabang()
    {
        return $this->hasOne(
            Cabang::class,'id', 'cabang_id'
        );
    }
    public function obat()
    {
        return $this->hasMany(
            DetailObat::class,'id','produk_id'
        );
    }

    public function stokop()
    {
        return $this->hasOne(StokOpname::class,'produk_id','id');
    }
}
