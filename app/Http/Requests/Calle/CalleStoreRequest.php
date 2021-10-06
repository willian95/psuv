<?php

namespace App\Http\Requests\Calle;

use Illuminate\Foundation\Http\FormRequest;

class CalleStoreRequest extends FormRequest
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
            "nombre" => "required|max:50",
            "tipo" => "required",
            "sector" => "required",
            "comunidad_id" => "required|exists:comunidad,id"
        ];
    }
}
