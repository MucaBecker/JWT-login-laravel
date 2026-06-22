<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;
    #[Test]
    public function it_returns_a_token_for_a_valid_user(): void{
        $user = User::factory()->create();
        $authService = $this->app->make(AuthService::class);
        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $token = $authService->attemptLogin($credentials);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+$/', $token);
    }

    #[Test]
    public function it_throws_authentication_exception_for_invalid_credentials(): void{
        $user = User::factory()->create();
        $authService = $this->app->make(AuthService::class);
        $credentials = [
            'email' => $user->email,
            'password' => 'wrongPassword',
        ];

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('The credentials provided are incorrect.');
        $authService->attemptLogin($credentials);
    }
}
