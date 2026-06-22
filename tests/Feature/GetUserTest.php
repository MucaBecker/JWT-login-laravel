<?php

namespace Tests\Feature;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetUserTest extends TestCase
{
    use DatabaseTransactions;
    private string $route = '/api/user/';

    #[Test]
    public function it_returns_401_if_not_authenticated(): void{
        $response = $this->get($this->route . '1');
        $response->assertStatus(401);
    }

    #[Test]
    public function it_returns_404_if_user_not_found(): void{
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get($this->route . '000');
        $response->assertStatus(404);
    }

    #[Test]
    public function it_returns_the_logged_in_user(): void{
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get($this->route . $user->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
    }
}
