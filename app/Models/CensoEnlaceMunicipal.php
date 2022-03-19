<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CensoEnlaceMunicipal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'censo_enlace_municipal';

    public function personalCaracterizacions()
    {
        return $this->belongsTo(PersonalCaracterizacion::class, 'raas_personal_caracterizacion_id', 'id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'raas_municipio_id', 'id');
    }
}
