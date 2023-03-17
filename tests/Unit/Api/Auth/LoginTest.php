<?php

namespace Tests\Unit\Api\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_login_successful()
    {
        $password = Str::random(10);
        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $response = $this->postJson('/api/oauth/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(200);
    }

    /**
     *
     * @return void
     */
    public function test_login_wrong_email()
    {
        $response = $this->postJson('/api/oauth/login', [
            'email' => Str::random(10),
            'password' => Str::random(10),
        ]);

        $response->assertJsonValidationErrors(['email']);
    }

        /**
     *
     * @return void
     */
    public function test_login_wrong_password()
    {
        $response = $this->postJson('/api/oauth/login', [
            'email' => User::first()->email,
            'password' => Str::random(10),
        ]);

        $response->assertJsonValidationErrors(['password']);
    }
}
