<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Requests\User\UpdateUserRequest;

class UpdateUser extends Controller
{
    public function __invoke($id, UpdateUserRequest $request)
    {
        $user = User::findOrFail($id);

        $user->update($request->only([
            'name',
            'email',
            'password',
            'group_role',
            'is_active',
        ]));

        return response()->json([
            'message' => 'successful',
            'error' => []
        ]);
    }
}
