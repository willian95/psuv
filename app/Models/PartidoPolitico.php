<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidoPolitico extends Model
{
    protected $table = 'elecciones_partido_politico';
    use HasFactory;

    public function personalCaracterizacions()
    {
        return $this->hasMany(PersonalCaracterizacion::class);
    }
}
