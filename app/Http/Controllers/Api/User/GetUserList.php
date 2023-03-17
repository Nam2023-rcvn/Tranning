<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Routing\Controller;
use App\Models\User;
use App\Http\Resources\User\UserResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\User\GetUserListRequest;

class GetUserList extends Controller
{
    public function __invoke(GetUserListRequest $request)
    {
        $users = $this->filterUsers($request);

        return UserResource::collection($users->paginate($request->perPage ?? 10));
    }

    public function filterUsers(GetUserListRequest $request): Builder
    {
        $users = User::latest();

        if ($request->filled('role')) {
            $users->where('group_role', $request->role);
        }

        if ($request->filled('email')) {
            $users->where('email', $request->email);
        }

        if ($request->filled('name')) {
            $users->where('name', 'like', '%'.$request->name.'%');
        }

        return $users;
    }
}
