<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfer';
    protected $primaryKey = 'id';
    protected $guarded = [];


    public function cabang()
    {
        return $this->belongsTo(Cabang::class,'penerima','id' );
    }
}
