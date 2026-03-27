<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data(): void
    {
        $this->postJson('/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'johndoe@wellnesslog.test',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(201)
            ->assertExactJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'johndoe@wellnesslog.test',
        ]);
    }

    public function test_user_cant_register_with_invalid_data(): void
    {
        $this->postJson('/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'johndoe@wellnesslog.test',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'name'  => 'John Doe',
            'email' => 'johndoe@wellnesslog.test',
        ]);
    }

    public function test_user_can_login_with_valid_data(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertStatus(200)
            ->assertExactJsonStructure(['token']);
    }

    public function test_user_cant_login_with_invalid_data(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'pass',
        ])->assertStatus(401)
            ->assertExactJson(['message' => 'Invalid credentials.']);
    }

    public function test_user_can_get_profile_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/me')
            ->assertStatus(200)
            ->assertExactJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'timezone',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_user_cant_get_profile_data_wo_token(): void
    {
        $this->getJson('/api/me')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_can_logout(): void
    {
        $token = User::factory()->create()
            ->createToken('test')
            ->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')
            ->assertStatus(200)
            ->assertExactJson(['message' => 'Logged out.']);

        $this->app['auth']->forgetGuards();

        $this->withToken($token)
            ->getJson('/api/me')
            ->assertStatus(401);
    }

    public function test_user_cant_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'johndoe@wellnesslog.test']);

        $this->postJson('/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'johndoe@wellnesslog.test',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])->assertStatus(422);
    }
}
