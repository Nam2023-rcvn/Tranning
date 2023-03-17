<?php

namespace Tests\Unit\Api\User;

use Tests\TestCase;
use App\Models\User;

class DeleteUserTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_delete_user_successful()
    {
        $user = User::factory()->create();

        $response = $this->delete('api/users/' . $user->id, [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user->createToken('oath_token')->accessToken
        ]);

        $response
        ->assertStatus(200);

        $this->assertModelExists($user);
    }
}
;
