<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'error' => [
                'type' => 'CredentialsException',
                'status' => 401,
                'code' => 'INVALID_CREDENTIALS',
                'message' => 'The provided credentials are invalid.',
            ],
        ], 401);
    }
}
