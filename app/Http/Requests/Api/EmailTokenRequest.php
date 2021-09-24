<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;


class EmailTokenRequest extends BaseRequest
{
    public function authorize()
    {
        parent::authorize();
        return true;   
    }
    
    public function __construct(){
        $this->addRule( 'POST' ,
        [
            'token' => 'bail|required',
            'new_password' => 'bail|required|string|max:20|min:8|confirmed'
        ]
    );
    }
}
