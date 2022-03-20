<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RaasJefeComunidad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'raas_jefe_comunidad';

    public function personalCaracterizacions()
    {
        return $this->belongsTo(PersonalCaracterizacion::class, 'raas_personal_caracterizacion_id', 'id');
    }

    public function jefeClap()
    {
        return $this->belongsTo(CensoJefeClap::class, 'censo_jefe_clap_id', 'id');
    }

    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'raas_comunidad_id', 'id');
    }
}
