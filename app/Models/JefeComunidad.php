<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeComunidad extends Model
{
    protected $table="jefe_comunidad";
    use HasFactory;

    public function personalCaracterizacion(){

        return $this->belongsTo(PersonalCaracterizacion::class);

    }

    public function comunidad(){

        return $this->belongsTo(Comunidad::class);

    }

    public function jefeUbch(){

        return $this->belongsTo(JefeUbch::class);

    }

}
