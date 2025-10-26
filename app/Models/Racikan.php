<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racikan extends Model
{
    use HasFactory;

    protected $table = 'racikan';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
