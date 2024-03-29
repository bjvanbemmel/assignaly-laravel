<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): UserResource
    {
        $request = $request->validated();

        return UserResource::make(User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'institute_id' => $request['institute_id'],
            'role_id' => $request['role_id'],
        ]));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => UserResource::make($request->user()),
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request): bool
    {
        return $request->user()->tokens()->delete();
    }

    public function get(Request $request): ?UserResource
    {
        return UserResource::make($request->user());
    }
}
