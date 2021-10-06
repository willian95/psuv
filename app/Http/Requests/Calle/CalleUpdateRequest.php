<?php

namespace App\Http\Requests\Calle;

use Illuminate\Foundation\Http\FormRequest;

class CalleUpdateRequest extends FormRequest
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
            "nombre" => "required|max:140|min:2",
            "tipo" => "required|max:50|min:2",
            "sector" => "required|max:100|min:2",
            "comunidad_id" => "required|exists:comunidad,id"
        ];
    }
}
