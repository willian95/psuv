<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeCalle extends Model
{
    use HasFactory;
    protected $table="raas_jefe_calle";
    protected $fillable=[
        "raas_calle_id",
        "raas_personal_caracterizacion_id",
        "raas_jefe_comunidad_id",
    ];
    public function calle(){
        return $this->belongsTo(Calle::class,"raas_calle_id");
    }
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"raas_personal_caracterizacion_id");
    }
    public function JefeComunidad(){
        return $this->belongsTo(JefeComunidad::class,"raas_jefe_comunidad_id");
    }
    public function jefeFamilias(){
        return $this->hasMany(JefeFamilia::class,"jefe_calle_id");
    }

    public function calles(){
        return $this->hasMany(JefeCalle::class,"personal_caraterizacion_id","raas_personal_caracterizacion_id");
    }
}
