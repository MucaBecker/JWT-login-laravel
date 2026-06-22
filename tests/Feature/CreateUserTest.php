<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use DatabaseTransactions;
    private string $route = '/api/user';

    public static function invalidUserDataProvider(): array{
        return [
            "missing email" => [
                ["name" => "Test", "password" => "password","password_confirmation" => "password"],
                "The email field is required."
            ],
            "missing name" => [
                ["email" => "test@test.com", "password" => "password","password_confirmation" => "password"],
                "The name field is required."
            ],
            "missing password" => [
                ["email" => "test@test.com", "name" => "test","password_confirmation" => "password"],
                "The password field is required."
            ],
            "missing password confirmation" => [
                ["email" => "test@test.com", "name" => "test", "password" => "password"],
                "The password field confirmation does not match."
            ],
            "invalid email field" => [
                ["email" => "test", "name" => "test", "password" => "password","password_confirmation" => "password"],
                "The email field must be a valid email address."
            ],
            "invalid name type" => [
                ["email" => "test@test.com", "name" => 123, "password" => "password","password_confirmation" => "password"],
                "The name field must be a string."
            ],
            "invalid password size" => [
                ["email" => "test@test.com", "name" => "test", "password" => "pass","password_confirmation" => "pass"],
                "The password field must be at least 6 characters."
            ],
            "invalid password type" => [
                ["email" => "test@test.com", "name" => "test", "password" => 123456,"password_confirmation" => 123456],
                "The password field must be a string."
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidUserDataProvider')]
    public function it_rejects_invalid_registration_data(array $payload, string $expectedMessageError): void{
        $response = $this->postJson($this->route, $payload);

        $response->assertJsonFragment(["message" => $expectedMessageError])->assertStatus(422);
    }

    #[Test]
    public function it_rejects_an_existing_email(): void{
        $user = User::factory()->create();
        $payload = [
            "name" => "Test",
            "email" => $user->email,
            "password" => "password",
            "password_confirmation" => "password"
        ];

        $response = $this->postJson($this->route, $payload);

        $response->assertJsonFragment(["message" => "The email has already been taken."])->assertStatus(422);
    }

    #[Test]
    public function it_creates_a_new_user_successfully(): void{
        $payload = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];
        $response = $this->postJson($this->route, $payload);

        $response->assertJson([
            'message' => "User created successfully!"
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
            'name' => 'test'
        ]);
    }
}
