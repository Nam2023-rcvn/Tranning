<?php

namespace Tests\Unit\Api\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class GetUserTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_get_user_successful()
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('oath_token')->accessToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('/api/users/'. $user->id);

        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('data', function (AssertableJson $json) use ($user) {
                $json->where('id', $user->id)
                ->where('name', $user->name)
                ->where('email', fn (string $email) => str($email)->is($user->email))
                ->etc();
            });
        });
    }
}
