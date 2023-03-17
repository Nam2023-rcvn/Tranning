<?php

namespace Tests\Unit\Api\User;

use Tests\TestCase;
use App\Models\User;

class UpdateUser extends TestCase
{
    private $user = null;
    private $accessToken = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->accessToken = $this->user->createToken('oath_token')->accessToken;
    }

    /**
     *
     * @return void
     */
    public function test_create_user_validate()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ])->put('/api/users/'.$this->user->id);

        $response
        ->assertJsonValidationErrors(['name']);

        $response
            ->assertJsonValidationErrors(['email']);

        $response
        ->assertJsonValidationErrors(['password']);
    }

        /**
     *
     * @return void
     */
    public function test_create_user_successful()
    {
        $data = User::factory()->definition();
        $data['password_confirmation'] = $data['password'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken
        ])->put('/api/users/'. $this->user->id, $data);

        $response
        ->assertStatus(200);
    }
}
