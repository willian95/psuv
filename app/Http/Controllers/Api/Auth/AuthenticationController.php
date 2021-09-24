<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Validator, Auth, Gate, Log};
use App\Models\{User};
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationController extends Controller
{
	public function authentication($role, Request $request)
	{
		$credentials = $request->only('email', 'password'); //Get credentials

		try {
			if (!isset($request->email)) {
				return response()->json(["message" => 'El correo electrónico es requerido.'], 400);
			}
			
			// Verify if password is in request
			if (!is_null($request->password)) {
				// Try to login with credentials
				if (!$token = auth()->attempt($credentials)) {
					throw new JWTException('Credenciales inválidas', 400);
				}
				$user =auth()->user();
				$response = [
					"data" => $token,
					"user" => new UserCollection($user),
					"message" => "Autenticación exitosa"
				];
			} else {
				return response()->json(["message" => 'La contraseña es requerida.'], 400);
				
			}
		} catch (JWTException $e) {
			$code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
			$msg = $e->getMessage() ?? "No se ha podido crear el token de usuario";
			$response = [
				"message" => $msg
			];
			$detail = [
				"line" => $e->getLine(),
				"msg" => $e->getMessage(),
				"file" => $e->getFile()
			];
			Log::info($detail);
		} //catch
		return response()->json($response, $code ?? 200);
	}

	public function getAuthenticatedUser(Request $request)
	{
		$with = $request->input('with') ?? [];
		$managerType = $request->input('manager_type') ?? null;

		if (!$user = auth()->user())
			return response()->json(["message" => "Usuario no encontrado"], 404);
		
		$user->loadProfile($with, $managerType);

		if ($user->relationLoaded('manager') && $managerType && !empty($user->manager)) {
			Gate::authorize('approved-manager',$user->manager);
		}
		
		return $this->response([
			"data" => new UserCollection($user),
			"message" => "Datos de usuario autenticado"
		], $code ?? 200);
	}

	public function logout()
	{
		JWTAuth::invalidate(JWTAuth::getToken());
		return $this->response(["message" => "Sesión cerrada"], 200);
	}

	public function verifyEmailToken(Request $request){
		$token = $request->token ?? null;

		DB::beginTransaction();
		try {
			if (!$token) {
				throw new \Exception("Token not found", 404);
			}

			$user = User::where('verification_token', $token)->first();
			if (empty($user->id)) {
				throw new \Exception("Token invalido", 400);
			}

			if (!empty($user->email_verified_at)) {
				throw new \Exception("El usuario ya ha sido verificado", 400);
			}

			$user->email_verified_at = now();
			$user->save();
			$view = 'success';
			$data = [];
		} catch (\Exception $e) {
			DB::rollBack();
			$code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
			$msg = $e->getMessage() ?? "No se ha podido crear el token de usuario";
			$view = 'error';
			$data = [
				'code' => $code,
				'message' => $msg
			];
		}

		DB::commit();
		return response(view($view, $data), $code ?? 200);
	}

	public function resendVerifyEmail(Request $request){
		$user = User::where('email', $request->email)->first();
		try {
			if (empty($user->id)) {
				throw new \Exception("Datos invalidos", 400);
			}
			$user->sendVerification();
			$code = 200;
			$response = $this->getSuccessResponse(null, $client_message ?? 'Se ha reenviado el email de verificación.');
		} catch (\Exception $e) {
			DB::rollBack();
			$code = $this->getCleanCode($e);
			$response = $this->getErrorResponse($e, 'Ocurrio algo inesperado al intentar enviar el email de verificación del usuario');
			$detail = [
				"line" => $e->getLine(),
				"msg" => $e->getMessage(),
				"file" => $e->getFile()
			];
			Log::info($detail);
		}

		return $this->response($response, $code);
	}
}
