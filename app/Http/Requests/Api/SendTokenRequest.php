<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;


class SendTokenRequest extends BaseRequest
{

    public function authorize()
    {
        parent::authorize();
        return true;
    }

    public function __construct(){

        $this->addRule( 'POST' , [
            'email' => 'bail|required|email|exists:users,email',
        ]);
    }

}
