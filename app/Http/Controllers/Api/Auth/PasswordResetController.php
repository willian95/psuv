<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\{SendTokenRequest, EmailTokenRequest};
use App\Jobs\Email\SendTokenJob;
use App\Services\SendToken;
use App\Models\{User, PasswordReset};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{DB};

class PasswordResetController extends Controller
{
	public function verifyEmail($role,SendTokenRequest $request)
	{
		try {
			DB::beginTransaction();
			$entity = User::whereEmail($request->email)->first();
			
			if (!$entity->hasRole($role)) {
				throw new \Exception("No estas autorizado para este request.", 401);
			}

			if (empty($entity->password) && $entity->hasRole('manager')) {
				throw new \Exception("No estas habilitado para recuperar contraseña aun.", 401);
			}

			$password_reset = $entity->password_reset;
			
			if (!empty($password_reset->email)) {
				$count = $password_reset->attempts;
				$deleted = false;

				if($count >= 3){
					if($password_reset->updated_at->diffInMinutes(now()) >= 60){
						$deleted = true;
						$password_reset->delete();
					}else{
						$await_minutes = 60 - $password_reset->updated_at->diffInMinutes(now()) . ' minutos.';

						throw new \Exception("Ya ha intentado muchas veces, por favor intentelo en " . $await_minutes, 400);
					}
				}

				if(!$deleted){
					$password_reset->attempts += 1;
					$password_reset->save();
				}
			}

			dispatch(new SendTokenJob($entity,23948717));
			
			DB::commit();

			$response = $this->getSuccessResponse('', "Se ha enviado un correo electrónico para cambiar la contraseña");
		} catch (\Exception $e) {
			DB::rollBack();

			$code = $this->getCleanCode($e);
			$response = $this->getErrorResponse($e, 'Error al intentar reiniciar la contraseña');
		} //catch

		return $this->response($response, $code ?? 200);
	} //verifyEmail()

	public function resetPassword(EmailTokenRequest $request)
	{
		try {

			DB::beginTransaction();
			
			$token = md5($request->input('token'));
			$entity = PasswordReset::where('token', $token)->firstOrFail();
			$dateExpireToken = $entity->created_at->addHours(11);

			if (now()->diffInHours($dateExpireToken) <= 0)
				$this->executeMessageError('El token ha expirado');

			$user = $entity->user;
			$user->password = bcrypt($request->new_password);
			$user->password_updated_at = now();

			if($user->save()){
				PasswordReset::where('email', $user->email)->delete();
			}

			$code = 200;
			$response = $this->getSuccessResponse(null,"Cambio de clave exitoso.");
			DB::commit();
		} catch (ModelNotFoundException $e) {
			DB::rollBack();
			$code = $this->getCleanCode($e);
			$response = ['status' => 'error', 'message' => 'El token enviado es invalido.'];
		} catch (\Exception $e) {
			DB::rollBack();
			$code = $this->getCleanCode($e);
			$response = $this->getErrorResponse($e, 'Error al intentar reiniciar la contraseña');
		} //catch

		return $this->response($response, $code);
	} //verifyEmail()
}
