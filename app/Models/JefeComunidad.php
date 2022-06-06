<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class JefeComunidad extends Model
{
    protected $table="raas_jefe_comunidad";
    use SoftDeletes;
    use HasFactory;

    public function personalCaracterizacion(){

        return $this->belongsTo(PersonalCaracterizacion::class,"raas_personal_caracterizacion_id");

    }

    public function comunidad(){

        return $this->belongsTo(Comunidad::class);

    }

    public function comunidades(){
        return $this->hasMany(JefeComunidad::class,"personal_caracterizacion_id","personal_caracterizacion_id")->with('comunidad');
    }

    public function jefeUbch(){

        return $this->belongsTo(JefeUbch::class);

    }

}
