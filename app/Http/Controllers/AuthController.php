<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->attempt(
            $request->validated('email'),
            $request->validated('password'),
        );

        if ($result === null) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect.',
            ], 401);
        }

        return response()->json($result);
    }
    
    
    public function register(RegisterRequest $request): JsonResponse
    {
        return response()->json($this->auth->register($request->validated()), 201);
    }
    

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
    

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());

        return response()->json(['message' => 'Déconnecté.']);
    }
}