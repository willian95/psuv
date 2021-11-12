<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = "mesa";

    public function participacionCentroVotacions(){
        return $this->hasMany(ParticipacionCentroVotacion::class);
    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }

}
