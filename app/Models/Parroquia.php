<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    protected $table="parroquia";
    use HasFactory;

    public function personalCaracterizacions(){

        return $this->hasMany(PersonalCaracterizacion::class);

    }
}
