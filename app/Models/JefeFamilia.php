<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeFamilia extends Model
{
    use HasFactory;
    protected $table="raas_jefe_familia";
    protected $fillable=[
        "personal_caraterizacion_id",
        "jefe_calle_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"raas_personal_caracterizacion_id", "id");
    }
    public function JefeCalle(){
        return $this->belongsTo(RaasJefeCalle::class,"raas_jefe_calle_id", "id");
    }
    public function familiares(){
        return $this->hasMany(PersonalCaracterizacion::class,"raas_jefe_familia_id");
    }
    public function vivienda(){
        return $this->hasOne(CensoVivienda::class, "raas_jefe_familia_id", "id");
    }
}
