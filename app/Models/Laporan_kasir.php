<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan_kasir extends Model
{
    use HasFactory;

    protected $table = 'laporan_kasir';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }
    public function buka()
    {
        return $this->hasOne(User::class, 'id', 'yg_buka');
    }
    public function tutup()
    {
        return $this->hasOne(User::class, 'id', 'yg_nutup');
    }
}