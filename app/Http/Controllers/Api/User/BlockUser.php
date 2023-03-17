<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Requests\User\BlockUserRequest;

class BlockUser extends Controller
{
    public function __invoke($id, BlockUserRequest $request)
    {
        $user = User::findOrFail($id);

        $user->update(['is_active' => $user->is_active !== User::ACTIVE]);

        return response()->json([
            'message' => 'successful',
            'error' => []
        ]);
    }
}
