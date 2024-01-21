<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;

    public $table = 'produccions';
    public $fillable = [
        'cant_producida',
        'cant_trabajadores',
        'vitola_id',
        'brigada_id',
        'fecha_id'
    ];

    
    public function vitola()
    {
        return $this->belongsTo(Vitola::class);
    }

    public function brigada()
    {
        return $this->belongsTo(Brigada::class);
    }

    public function fecha()
    {
        return $this->belongsTo(Fecha::class);
    }

}
