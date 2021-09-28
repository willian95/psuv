<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elector extends Model
{
    protected $table="elector";
    use HasFactory;
    protected $appends=[
        "full_name"
      ];
    
    public function getFullNameAttribute()
    {
        $name = $this->primer_nombre;
        if (!empty($this->segundo_nombre)) {
            $name .= ' ' .$this->segundo_nombre;
        }
        if (!empty($this->primer_apellido)) {
            $name .= ' ' .$this->primer_apellido;
        }
        if (!empty($this->segundo_apellido)) {
            $name .= ' ' .$this->segundo_apellido;
        }
        return $name;
    }
}
