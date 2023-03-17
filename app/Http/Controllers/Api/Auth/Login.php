<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Routing\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Reponse\BaseHttpResponse;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    public function __invoke(LoginRequest $request, BaseHttpResponse $reponse)
    {
        if (Auth::attempt($request->only(['email', 'password']), $request->remember ?? false)) {
            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);

            $accessToken = $user->createToken('oath_token')->accessToken;

            return response()
            ->json([
                'user' => UserResource::make($user),
                'accessToken' => $accessToken
            ]);
            ;
        }

        return response()
        ->json([
            'message' => 'Mật khẩu không chính xác.',
            'errors' => [
                "password"=> [
                    "Mật khẩu không chính xác."
                ]
                ],
        ], 422);
    }
}
