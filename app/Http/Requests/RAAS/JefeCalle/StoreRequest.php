<?php

namespace App\Http\Requests\RAAS\JefeCalle;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "jefe_comunidad_id" => "required|exists:personal_caracterizacion,id",
            "personal_caraterizacion" => "required",
            "tipo_voto" => "required",
            "telefono_principal" => "required|max:11",
            "telefono_secundario" => "required|max:11",
            "partido_politico_id" => "required|exists:partido_politico,id",
            "movilizacion_id" => "required|exists:movilizacion,id",
            "calle_id" => "required|exists:calle,id"
        ];
    }
}
