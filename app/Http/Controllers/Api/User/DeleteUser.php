<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Requests\User\DeleteUserRequest;

class DeleteUser extends Controller
{
    public function __invoke($id, DeleteUserRequest $request)
    {
        $user = User::findOrNew($id);

        $user->delete();

        return response()->json([
            'message' => 'successful',
            'errors' => []
        ]);
    }
}
