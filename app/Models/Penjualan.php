<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }
    public function laporan_kasir()
    {
        return $this->hasOne(Laporan_kasir::class, 'id_kasir', 'shift');
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class, 'jasa_id', 'id');

    }

    public function metod()
    {
        return $this->belongsTo(Metode::class, 'metode_id', 'id');

    }
  
   public function details()
   {
       return $this->hasMany(PenjualanDetail::class, 'penjualan_id', 'id');
   }
    
}
