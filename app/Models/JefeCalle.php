<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeCalle extends Model
{
    protected $table="jefe_calle";
    protected $fillable=[
        "calle_id",
        "personal_caraterizacion_id",
        "jefe_comunidad_id",
    ];
    use HasFactory;
    public function calle(){
        return $this->belongsTo(Calle::class);
    }
    public function personalCaracterizacion(){
        return $this->belongsTo(personalCaracterizacion::class,"personal_caraterizacion_id");
    }
    public function JefeComunidad(){
        return $this->belongsTo(JefeComunidad::class);
    }
}
