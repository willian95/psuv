<?php

namespace App\Http\Requests\RAAS\UBCH;

use Illuminate\Foundation\Http\FormRequest;

class UBCHUpdateRequest extends FormRequest
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
            "centro_votacion_id" => "required|exists:centro_votacion,id",
            "cedula" => "required",
            "tipo_voto" => "required",
            "telefono_principal" => "required|max:11",
            "telefono_secundario" => "required|max:11",
            "partido_politico_id" => "required|exists:partido_politico,id",
            "movilizacion_id" => "required|exists:movilizacion,id"
        ];
    }
}
