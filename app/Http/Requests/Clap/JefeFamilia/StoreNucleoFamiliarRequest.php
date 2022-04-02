<?php

namespace App\Http\Requests\Clap\JefeFamilia;

use Illuminate\Foundation\Http\FormRequest;

class StoreNucleoFamiliarRequest extends FormRequest
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
            'jefeFamiliaId' => 'required',
            'cedula' => 'required',
            'telefono_principal' => 'nullable|max:11',
            'telefono_secundario' => 'nullable|max:11',
            'partido_politico_id' => 'nullable|exists:elecciones_partido_politico,id',
            'movilizacion_id' => 'nullable|exists:elecciones_movilizacion,id',
            'fecha_nacimiento' => 'required',
            'raas_estatus_personal_id' => 'required'
        ];
    }
}
