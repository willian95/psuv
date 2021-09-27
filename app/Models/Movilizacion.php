<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movilizacion extends Model
{
    protected $table="movilizacion";
    use HasFactory;

    public function personalCaracterizacions(){

        return $this->hasMany(PersonalCaracterizacion::class);

    }
}
