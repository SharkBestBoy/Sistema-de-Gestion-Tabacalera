<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    public $table = 'produccions';
    public $fillable = [
        'cantidad',
        'vitola_id',
        'brigada_id'
    ];
}
