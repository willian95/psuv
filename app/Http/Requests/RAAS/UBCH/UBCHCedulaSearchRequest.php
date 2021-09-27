<?php

namespace App\Http\Requests\RAAS\UBCH;

use Illuminate\Foundation\Http\FormRequest;

class UBCHCedulaSearchRequest extends FormRequest
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
            "cedulaJefe" => "required|exists:personal_caracterizacion,cedula"
        ];
    }
}
