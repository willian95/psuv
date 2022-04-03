<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderOperacionesRequest extends FormRequest
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
            "selectedOperacion" => "required",
            "rangoMenor" => "required_if:selectedOperacion,!=,menor|integer|nullable",
            "rangoMayor" => "required_if:selectedOperacion,!=,mayor|integer|nullable",
            "cantidadBolsas" => "required|integer"
        ];
    }
}
