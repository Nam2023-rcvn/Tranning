<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\GetUserRequest;

class GetUser extends Controller
{
    public function __invoke($id, GetUserRequest $request)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }
}
