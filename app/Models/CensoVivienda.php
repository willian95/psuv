<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CensoVivienda extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "censo_vivienda";

    public function censoTipoVivienda()
    {
        return $this->belongsTo(CensoTipoVivienda::class, 'censo_tipo_vivienda_id', 'id');
    }

    public function calle()
    {
        return $this->belongsTo(Calle::class, 'raas_calle_id', 'id');
    }
}
