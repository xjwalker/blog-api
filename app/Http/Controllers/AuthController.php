<?php


namespace App\Http\Controllers;


class AuthController extends Controller
{

    //Please add this method
    public function login() {
        // get email and password from request
        $credentials = request(['email', 'password']);

        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can omit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
        ]);
    }
}
