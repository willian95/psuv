<?php

namespace App\Http\Middleware;

use Closure, JWTAuth, Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , $roles = '')
    {
        try {
            $roles = explode('|' , $roles);
            $control = false;
            $user = auth()->user();

            foreach ($roles as $rol)
                if($user->hasRole($rol)) $control = true;

            if(!$control)
                throw new Exception('Usuario no autorizado user' , 401);

        } catch (Exception $e) {

            $code = $e->getCode() ? (is_numeric($e->getCode()) ? $e->getCode() : 500) : 500;
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'] , 400 );
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 400);
            }else{
                return response()->json(['status' => $e->getMessage() ?? 'Authorization Token not found'],  400);
            }
        }
        return $next($request);
    }
}
