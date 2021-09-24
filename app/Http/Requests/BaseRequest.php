<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class BaseRequest extends FormRequest
{
    protected $rules = [
        'POST'   => [],
        'PUT'    => [],
        'GET'    => [],
        'DELETE' => [],
    ];

    protected $controlMultiDelete = true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules[$this->method()];
    }

    protected function failedValidation(Validator $validator)
    {
        if($validator->fails()){
            $response=[
                "message"=>"Validar datos de registro",
                "errors"=>$validator->errors()
            ];
            throw new HttpResponseException(response()->json($response, 400));
        }
    }

    protected function getValueParametre($key){
        $key = $key ?? 'id';
        return $this->route()->parameters[$key] ?? NULL;
    }

    protected function prepareForValidation()
    {
        if($this->method() == 'DELETE' ){
            $id = $this->getValueParametre('id');
            if(is_numeric($id)){
                $this->merge([
                    'id' => [$id]
                ]);
                $this->controlMultiDelete = false;
            }
        }

        if($this->method() == 'PUT' ){
            $id = $this->getValueParametre('id');
            if(is_numeric($id) && !empty($id)){
                $this->merge([
                    'id' => [$id]
                ]);
                $this->controlMultiDelete = false;
            }
        }
    }

    protected function addRule($method , $rule){
        $this->rules[$method] = $rule;
    }

    protected function addClassInRuleDelete($class , $field = 'id'){
        $this->rules['DELETE']['id.*'] =  ['required','numeric', "exists:{$class},{$field}"];
    }

}
