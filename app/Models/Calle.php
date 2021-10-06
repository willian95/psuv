<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calle extends Model
{
    protected $table="calle";
    use HasFactory;
    protected $fillable=[
        "nombre",
        "tipo",
        "sector",
        "comunidad_id",
    ];

    
    public function comunidad(){
        return $this->belongsTo(Comunidad::class,"comunidad_id");
    }
}
