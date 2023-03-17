<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Requests\User\CreateUserRequest;

class CreateUser extends Controller
{
    public function __invoke(CreateUserRequest $request)
    {
        User::create($request->only([
            'name',
            'email',
            'password',
            'group_role',
            'is_active',
        ]));

        return response()->json([
            'message' => 'successful',
            'error' => [],
            'created' => true
        ], 201);
    }
}
