<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalCaracterizacion extends Model
{
    protected $table = 'raas_personal_caracterizacion';
    use HasFactory;
    protected $appends = [
        'full_name',
      ];
    protected $fillable = [
        'cedula',
        'nacionalidad',
        'nombre_apellido',
        'sexo',
        'telefono_principal',
        'telefono_secundario',
        'fecha_nacimiento',
        'tipo_voto',
        'inhabilitado_politicio',
        'raas_estado_id',
        'raas_municipio_id',
        'raas_parroquia_id',
        'raas_centro_votacion_id',
        'elecciones_partido_politico_id',
        'elecciones_movilizacion_id',
        'jefe_familia_id',
    ];

    public function getFullNameAttribute()
    {
        $name = $this->primer_nombre;
        // if (!empty($this->segundo_nombre)) {
        //     $name .= ' ' .$this->segundo_nombre;
        // }
        if (!empty($this->primer_apellido)) {
            $name .= ' '.$this->primer_apellido;
        }
        // if (!empty($this->segundo_apellido)) {
        //     $name .= ' ' .$this->segundo_apellido;
        // }
        return $name;
    }

    public function jefeUbchs()
    {
        return $this->hasMany(JefeUbch::class);
    }

    public function jefeComunidads()
    {
        return $this->hasMany(JefeComunidad::class);
    }

    public function jefeFamilia()
    {
        return $this->hasMany(JefeFamilia::class, 'jefe_familia_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function centroVotacion()
    {
        return $this->belongsTo(CentroVotacion::class);
    }

    public function partidoPolitico()
    {
        return $this->belongsTo(PartidoPolitico::class, 'elecciones_partido_politico_id', 'id');
    }

    public function movilizacion()
    {
        return $this->belongsTo(Movilizacion::class, 'elecciones_movilizacion_id', 'id');
    }
}
