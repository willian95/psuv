<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CensoJefeClap extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'censo_jefe_clap';

    public function personalCaracterizacions()
    {
        return $this->belongsTo(PersonalCaracterizacion::class, 'raas_personal_caracterizacion_id', 'id');
    }

    public function censoClap()
    {
        return $this->belongsTo(CensoClap::class, 'censo_clap_id', 'id');
    }
}
