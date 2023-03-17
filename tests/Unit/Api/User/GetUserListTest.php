<?php

namespace Tests\Unit\Api\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class GetUserListTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_get_users_list_successful()
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('oath_token')->accessToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('/api/users');

        $response
        ->assertJson(function (AssertableJson $json) {
            return $json->has('data')->etc();
        });
    }
}
