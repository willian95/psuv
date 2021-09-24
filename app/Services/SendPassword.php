<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Postmark\PostmarkClient;

class SendPassword
{
    private $user;
    private $password;
    public function __construct(User $user,$password=null)
    {
        $this->user=$user;
        $this->password=$password;
    }

    public function send(){
      $password=$this->password ? $this->password : $this->generatePassword();
      if ($this->updateUser($password)){
        $this->emailRender($password);
      }
    }
    private function generatePassword(): string
    {
        return Str::random(12);
    }

    private function emailRender($password){
      try {
        $client = new PostmarkClient(env('POSTMARK_TOKEN'));
        $clientemail = $client->sendEmailWithTemplate(
          env('MAIL_FROM_ADDRESS'),
          $this->user->email,
          22778408,
          [
            "name" => $this->user->name,
            "email" => $this->user->email,
            "password" => $password,
          ]
        );
      } catch (\Exception $e) {
          \Log::error($e->getMessage());
      }

    }
    private function updateUser($password){

        try {
            $data=[
              "password"=> bcrypt($password)
            ];
            $this->user->update($data);
            return  true;
        }catch (\Exception $e){
            \Log::error($e->getMessage());

            return false;
        }


    }

}
