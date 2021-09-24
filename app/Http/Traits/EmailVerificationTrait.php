<?php

namespace App\Http\Traits;

use App\Services\SendToken;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait EmailVerificationTrait
{
    public function sendVerification($profile_name = null)
    {
        if ($this->email_verified_at != null) {
            throw new \Exception("El usuario ya se encuentra verificado", 400);
        }

        $url_token = (string) $this->generateUrl();
        $mailable = new SendToken($this, ['url_link' => $url_token, 'profile_name' => $profile_name ?? "Cliente"], 24486549);
        $mailable->sendNotify();
        return true;
    }

    public function generateToken()
    {
        return Str::random();
    }

    public function generateUrl()
    {
        if (empty($this->verification_token)) {
            throw new Exception("No hay token de verificación.", 404);
        }
        return url('email-verify/' . $this->verification_token);
    }
}
