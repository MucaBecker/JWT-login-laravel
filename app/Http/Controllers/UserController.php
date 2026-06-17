<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

/**
 * UserController class handles user-related operations.
 */
class UserController extends Controller
{
    #[OA\Post(
        path: "/api/login",
        summary: "Authenticate user and return JWT token",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Login successful",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "access_token", type: "string"),
                new OA\Property(property: "token_type", type: "string", example: "bearer")
            ]
        )
    )]
    #[OA\Response(response: 401, description: "Invalid credentials")]
    #[OA\Response(response: 422, description: "Validation error")]
    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $inputs = $request->validated();

        $token = $authService->attemptLogin($inputs);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    #[OA\Post(
        path: "/api/user",
        summary: "Register a new user",
        tags: ["User"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123")
                ]
            )
        )
    )]
    #[OA\Response(response: 201, description: "User created successfully", content: new OA\JsonContent(properties: [new OA\Property(property: "msg", type: "string")]))]
    #[OA\Response(response: 422, description: "Validation error")]
    #[OA\Response(response: 500, description: "Error creating user")]
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

    #[OA\Get(
        path: "/api/user/{id}",
        summary: "Get user details by ID",
        tags: ["User"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, description: "User ID", schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Success",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "msg", type: "string"),
                new OA\Property(property: "data", type: "object")
            ]
        )
    )]
    #[OA\Response(response: 401, description: "Unauthenticated")]
    #[OA\Response(response: 404, description: "User not found")]
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
