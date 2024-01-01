<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    private string $host = 'api';

    public User $user;
    public string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ThrottleRequests::class);
        $this->user = User::factory()->create();

        $response = $this->postJson($this->host . '/login', [
            'email'    => $this->user->email,
            'password' => "password",
        ]);

        $this->token = $response->json("access_token");
    }

    public function testUserCanLogIn(): void
    {
        $response = $this->post('/api/login');

        $response = $this->postJson($this->host . '/login', [
            'email'    => $this->user->email,
            'password' => "password",
        ])->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonCount(6, 'user')
            ->assertJsonStructure([
                "access_token",
            ]);


        $this->assertSame($response->json("user.email"), $this->user->email);
        $this->assertSame($response->json("user.id"), $this->user->id);
    }


    public function testUserCanLogOut(): void
    {
        $response = $this->withToken($this->token)
            ->deleteJson($this->host . '/auth/logout')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1);

        $this->assertSame($response->json("message"), "Successfully logged out");
    }

    public function testUserTokenCanRefresh(): void
    {
        $response = $this->withToken($this->token)
            ->postJson($this->host . '/auth/refresh')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonCount(6, 'user')
            ->assertJsonStructure([
                "access_token",
            ]);

        $this->assertSame($response->json("user.email"), $this->user->email);
        $this->assertSame($response->json("user.id"), $this->user->id);
    }

    public function testUserMeCanGetUser(): void
    {
        $response = $this->withToken($this->token)
            ->postJson($this->host . '/auth/me')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                "data" => [
                    "user" => [
                        "id",
                        "name",
                        "email",
                        "email_verified_at",
                        "created_at",
                        "updated_at",
                    ]
                ]
            ])
            ->assertJsonCount(3)
            ->assertJsonCount(6, 'data.user');

        $this->assertSame($response->json("data.user.email"), $this->user->email);
        $this->assertSame($response->json("data.user.id"), $this->user->id);
    }
}
