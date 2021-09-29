<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeFamilia extends Model
{
    use HasFactory;
    protected $table="jefe_familia";
    protected $fillable=[
        "personal_caraterizacion_id",
        "jefe_calle_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caraterizacion_id");
    }
    public function JefeCalle(){
        return $this->belongsTo(JefeCalle::class,"jefe_calle_id");
    }
}
