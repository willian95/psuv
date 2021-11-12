<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    public function cierreCandidatoVotacion(){

        return $this->belongsTo(CierreCandidatoVotacion::class, "candidatos_id");

    }

}
