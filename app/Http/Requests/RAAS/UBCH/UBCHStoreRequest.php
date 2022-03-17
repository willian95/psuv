<?php

namespace App\Http\Requests\RAAS\UBCH;

use Illuminate\Foundation\Http\FormRequest;

class UBCHStoreRequest extends FormRequest
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
            'centro_votacion_id' => 'required|exists:raas_centro_votacion,id',
            'cedula' => 'required',
            'municipio_id' => 'required|exists:raas_municipio,id',
            'parroquia_id' => 'required|exists:raas_parroquia,id',
            'telefono_principal' => 'required|max:11',
            'telefono_secundario' => 'nullable|max:11',
            'partido_politico_id' => 'nullable|exists:elecciones_partido_politico,id',
            'movilizacion_id' => 'nullable|exists:elecciones_movilizacion,id',
        ];
    }
}
