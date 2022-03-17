<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JefeUbch extends Model
{
    protected $table = 'raas_jefe_ubch';
    use HasFactory;
    use SoftDeletes;

    public function personalCaracterizacion()
    {
        return $this->belongsTo(PersonalCaracterizacion::class, 'raas_personal_caracterizacion_id', 'id');
    }

    public function jefeComunidas()
    {
        return $this->hasMany(JefeComunidad::class);
    }

    public function centroVotacion()
    {
        return $this->belongsTo(CentroVotacion::class, 'raas_centro_votacion_id', 'id');
    }
}
