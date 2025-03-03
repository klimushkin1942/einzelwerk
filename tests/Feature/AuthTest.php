<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldResponseOkForRegister()
    {
        $response = $this->post(
            route('register'),
            [
                'email' => 'test@example.com',
                'password' => 'password',
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'email',
            ]);

        $this->assertDatabaseHas(
            'users',
            [
                'email' => 'test@example.com',
            ]
        );
    }

    public function testShouldResponseUnprocessableEntityForRegister()
    {
        $response = $this->postJson(
            route('register'),
            [
                'email' => 'invalid-email',
                'password' => '',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function testShouldResponseOkForLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(
            route('login'),
            [
                'email' => $user->email,
                'password' => 'password',
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'email',
                ],
                'token',
            ]);
    }

    public function testShouldResponseUnprocessableEntityForLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(
            route('login'),
            [
                'email' => $user->email,
                'password' => '19421942',
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
