<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
                    $url = url('/instituciones-usuario');

                    return response()->json(['success' => true, 'msg' => 'Usuario autenticado', 'url' => $url]);
                } else {
                    return response()->json(['success' => false, 'msg' => 'Usuario no encontrado']);
                }
            } else {
                return response()->json(['success' => false, 'msg' => 'Usuario no encontrado']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Error en el servidor', 'err' => $e->getMessage(), 'ln' => $e->getLine()]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->to('/');
    }
}
