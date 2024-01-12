<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fecha extends Model
{

    public function planificacion()
    {
        return $this->belongsTo(Planificacion::class, 'planificacion_id');
    }
}
