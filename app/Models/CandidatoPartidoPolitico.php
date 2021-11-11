<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoPartidoPolitico extends Model
{
    use HasFactory;
    protected $table="candidatos_partido_politico";
    protected $fillable=[
        "candidatos_id",
        "partido_politico_id",
    ];
}
