<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registerParams = $request->all();
        if (!$registerParams || !isset($registerParams['login']) ||
            !isset($registerParams['name']) ||
            !isset($registerParams['email']) ||
            !isset($registerParams['password'])) {
            return response(['message' => 'Incorect input'], 400);
        }

        $user = User::create([
            'login' => $request->input('login'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response([
            'message' => 'User was registered',
            'user' => $user,
        ]);
    }

    public function login(LoginRequest $request)
    {

        $loginParams = $request->only(['login', 'password']);
        try {
            if ($token = JWTAuth::attempt($loginParams)) {
                $user = JWTAuth::user();

                return response([
                    'message' => 'User was logged',
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                    'user' => $user,
                ]);
            }
        } catch (TokenInvalidException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        } catch (JWTException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        }

        return response([
            'message' => 'Incorrect password',
        ], 400);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response(['message' => 'Successfully logged out']);
        } catch (TokenInvalidException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        } catch (JWTException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response(['token' => $token]);
        } catch (TokenInvalidException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        } catch (JWTException $exception) {
            return response(['JWT_error' => $exception->getMessage()], 401);
        }
    }

}
