<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaasJefeCalle extends Model
{
    use HasFactory;

    protected $table = 'raas_jefe_calle';

    public function personalCaracterizacions()
    {
        return $this->belongsTo(PersonalCaracterizacion::class, 'raas_personal_caracterizacion_id', 'id');
    }

    public function jefeComunidad()
    {
        return $this->belongsTo(RaasJefeComunidad::class, 'raas_jefe_comunidad_id', 'id');
    }

    public function calle()
    {
        return $this->belongsTo(Calle::class, 'raas_calle_id', 'id');
    }
}
