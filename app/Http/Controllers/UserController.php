<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $inputs = $request->validated();

        $token = $authService->attemptLogin($inputs);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    public function create(CreateUserRequest $request): JsonResponse{

        $inputs = $request->validated();

        $user = User::create($inputs);

        if(!$user){
            return response()->json([
                "msg" => "Error creating user!"
            ], 500);
        }

        return response()->json([
            'msg' => "User created successfully!"
        ], 201);
    }

    public function get(int $id): JsonResponse{
        $user = User::find($id);

        if(!$user){
            return response()->json([
                "msg" => "User not found!"
            ], 404);
        }

        return response()->json([
            "msg" => "Success",
            "data" => $user
        ]);
    }
}
