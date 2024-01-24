<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planificacion extends Model
{
    use HasFactory;
    public $table = 'planificacions';
    public $fillable = [
        'planificacionMensual',
        'diasLaborables'
    ];

    public function fechas()
    {
        return $this->hasMany(Fecha::class, 'planificacion_id');
    }
}
