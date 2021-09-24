<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Postmark\PostmarkClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use \Illuminate\Support\Facades\URL;

class SendToken
{
	private $user;
	private $template;
	private $data;

	public function __construct(User $user, array $data = null,int $template)
	{
		$this->user = $user;
		$this->template = $template;
		$this->data = $data;
	}

	public function send()
	{
		$token = $this->generateToken();
		
		if ($this->updateUser($token)) {
			$data = [
				"name" => $this->user->name,
				"email" => $this->user->email,
				"token" => $token,
			];
			$this->emailRender($data);
			return true;
		}
		return false;
	}

	public function sendNotify()
	{
		$data = [
			"name" => $this->user->name,
			"email" => $this->user->email,
		];

		if (!is_null($this->data)) {
			$data = array_merge($data,$this->data);
		}

		$this->emailRender($data);
	}

	private function generateToken(): string
	{
		return Str::random(6);
	}

	private function emailRender($data)
	{
		try {
			$response = Http::post('https://ms.innercron.dev.cronapis.com/api/v1/postmark/send/single',[
				'postmark_token' => '47eda940-4b38-4921-abb4-62dcb1271142',
				// 'postmark_token' => env('POSTMARK_TOKEN'),
				'template' => $this->template,
				'from' => env('MAIL_FROM_ADDRESS'),
				'to' => $this->user->email,
				'json_data' => $data
			]);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
		}
	}

	private function updateUser($token)
	{
		try {
			$this->user->password_reset()->updateOrCreate(["email" => $this->user->email],["token" => md5($token)]);
			return  true;
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			return false;
		}
	}

	private function getDataDefault($data = [])
	{
		$data['token'] = $data['token'] ?? '';
		$data['mail_for_support'] = env('MAIL_FOR_SUPPORT');
		$data['token'] = env('URL_REDIRECT_RECOVERTY_PASSWORD') . '/' . $data['token'];

		return $data;
	}
}
