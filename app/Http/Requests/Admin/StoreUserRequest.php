<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        parent::authorize();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function __construct()
    {
        $this->addRule('POST', [
            'name' => [
                'required',
                'string',
                'max:20'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:20'
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                'unique:users,email'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ] 
        ]);

        $this->addRule('PUT', [
            'name' => [
                'required',
                'string',
                'max:20'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:20'
            ],
            'email' => [
                'required',
                'email',
                'max:50',
                'unique:users,email'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
            ]
        ]);

        $this->addClassInRuleDelete(\App\Models\Document::class);
    }
}
