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

        if ($request->page == 1) {
            $users = $users->paginate(20);
            if ($users->total() > 20) {
                $users->setCollection($users->getCollection()->take(10));
                $users->resolveCurrentPage('page', 1);
            }
        } else {
            $users = $users->paginate($request->page_size ?? 10);
        }

        return UserResource::collection($users);
    }

    public function filterUsers(GetUserListRequest $request): Builder
    {
        $users = User::latest();

        if ($request->filled('role')) {
            $users->where('group_role', $request->role);
        }

        if ($request->filled('email')) {
            $users->where('email', 'like', '%'.$request->email.'%');
        }

        if ($request->filled('name')) {
            $users->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('status')) {
            $users->where('is_active', $request->status);
        }

        return $users;
    }
}
