<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class UserRequest extends BaseRequest
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

    public function __construct(Request $request){
        //Rules POST
        $rulesPost=[
            'name' => [
                'required',
                'string',
                'max:50'
            ],
            'last_name' => [
                'nullable',
                'string',
                'max:50'
            ],
            'phone' => [
                'nullable',
                'digits_between:7,20',
                Rule::unique('users','phone')
            ],
            'profile_image' => ['max:100'],
            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users','email')
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:20'
            ],
            "role_id" => [
                "required",
                "exists:roles,id"
            ]
        ];
        $this->addRule( 'POST' , $rulesPost);
        //Rules PUT
        $id = $request->route('id');
        $rulesPut=[
            'name' => [
                'string',
                'max:50'
            ],
            'last_name' => [
                'nullable',
                'string',
                'max:50'
            ],
            'phone' => [
                'nullable',
                'digits_between:7,20',
                Rule::unique('users','phone')->ignore($id, 'id')
            ],
            'profile_image' => ['max:100'],
            'email' => [
                'email',
                'max:50',
                Rule::unique('users','email')->ignore($id, 'id')
            ],
            'password' => [
                'confirmed',
                'min:8',
                'max:20'
            ],
            "role_id" => [
                "exists:roles,id"
            ]
        ];

        $this->addRule( 'PUT' , $rulesPut);
    }

}
