<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    private string $route = '/api/login';

    public static function invalidDataProvider(): array{
        return [
            "missing email" => [
                ["password" => "password"],
                "The email field is required."
            ],
            "missing password" => [
                ["email" => "test@test.com"],
                "The password field is required."
            ],
            "invalid email" => [
                ["email" => "test", "password" => "password"],
                "The email field must be a valid email address."
            ],
            "invalid password size" => [
                ["email" => "test@test.com", "password" => "pass"],
                "The password field must be at least 6 characters."
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidDataProvider')]
    public function it_rejects_a_request_with_invalid_data(array $payload, string $expectedMessageError): void{
        User::factory()->create(["email" => "test@test.com"]);
        $response = $this->postJson($this->route, $payload);    

        $response->assertJsonFragment(["message" => $expectedMessageError])->assertJsonStructure(["message", "errors"])->assertStatus(422);
    }

    #[Test]
    public function it_rejects_an_unregistered_user(): void{
        $payload = [
            "email" => "test@test.com",
            "password" => "password"
        ];
        $response = $this->postJson($this->route, $payload);

        $response->assertJsonFragment(["message" => "The selected email is invalid."])->assertJsonStructure(["message", "errors"])->assertStatus(422);
    }

    #[Test]
    public function it_returns_a_token_for_a_valid_user(): void{
        $user = User::factory()->create();
        $payload = [
            "email" => $user->email,
            "password" => "password"
        ];
        $response = $this->postJson($this->route, $payload);

        $response->assertJsonStructure([
            "access_token",
            "token_type"
        ])->assertStatus(200);

        $token = $response->json('access_token');
        $this->assertIsString($token);

        $authenticatedUser = JWTAuth::setToken($token)->authenticate();
        $this->assertEquals($user->id, $authenticatedUser->id);
    }
}
