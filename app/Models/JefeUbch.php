<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JefeUbch extends Model
{
    protected $table="jefe_ubch";
    use HasFactory;
    use SoftDeletes;

    public function personalCaracterizacion(){

        return $this->belongsTo(PersonalCaracterizacion::class);

    }

    public function jefeComunidas(){

        return $this->hasMany(JefeComunidad::class);

    }

}
