<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racikan_detail extends Model
{
    use HasFactory;

    protected $table = 'racikan_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

 
  public function produk()
  {
      return $this->belongsTo(Produk::class, 'produk_id', 'id');
  }
}
