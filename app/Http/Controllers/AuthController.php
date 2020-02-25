<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->input(['credentials']);
        /** @var User $user */
        $user = $request->input(['user']);

        return response()->json(['data' => $this->getAccessTokens($user)]);
    }

    public function signUp(SignUpRequest $request)
    {
        $user = new User();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('email'));
        $user->name = $request->input('username');
        $user->save();

        return response()->json(['message' => 'account_created']);
    }

    private function getAccessTokens(User $user)
    {
        return [
            'access_token' => $this->createToken($user, Carbon::now()->addHour(), 'access_token'),
            'refresh_token' => $this->createToken($user, Carbon::now()->addMonth(), 'refresh'),
        ];
    }

    private function createToken(User $user, Carbon $exp, $type)
    {
        return JWTAuth::fromUser($user, ['exp' => $exp->timestamp, 'type' => $type]);
    }
}
