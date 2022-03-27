<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CensoTipoVivienda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'censo_tipo_vivienda';
}
