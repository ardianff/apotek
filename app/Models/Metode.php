<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metode extends Model
{
    use HasFactory;

    protected $table = 'metode';
    protected $primaryKey = 'id';
    protected $guarded = [];

    
}
