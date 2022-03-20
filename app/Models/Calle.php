<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $table = 'raas_calle';
    use HasFactory;
    protected $fillable = [
        'nombre',
        'tipo',
        'sector',
        'raas_comunidad_id',
    ];

    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'raas_comunidad_id', 'id');
    }
}
