<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }
    public function authenticate(Request $request)
    {
        // return response()->json("");
        $credentials = $request->only('username', 'password');
         try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Cuenta o Contraseña incorrecta'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Error de coneccion'], 500);
        }
        if ($token = Auth::guard('api')->attempt($credentials)) {
            // return $this->respondWithToken($token);
        }
        $user = Auth::guard('api')->user();
        $user->token=$token;
        return  response()->json([
            // 'status' => 'ok',
            // 'token' => $token,
            'user' => $user
        ]);
    }
    public function getAuthenticatedUser(){
        // try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        // } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        //         return response()->json(['token_expired'], $e->getStatusCode());
        // } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        //         return response()->json(['token_invalid'], $e->getStatusCode());
        // } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        //         return response()->json(['token_absent'], $e->getStatusCode());
        // }

        return response()->json($user);
    }

}
