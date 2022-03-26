<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoClap extends Model
{
    use HasFactory;

    protected $table = 'censo_clap';

    public function comunidades()
    {
        return $this->hasMany(Comunidad::class, 'censo_clap_id', 'id');
    }
}
