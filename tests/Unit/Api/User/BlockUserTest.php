<?php

namespace Tests\Unit\Api\User;

use Tests\TestCase;
use App\Models\User;

class BlockUserTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_get_clock_successful()
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('oath_token')->accessToken;

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->put('/api/users/'. $user->id . '/clock');

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'is_active' => !($user->is_active !== User::ACTIVE)
        ]);
    }
}
