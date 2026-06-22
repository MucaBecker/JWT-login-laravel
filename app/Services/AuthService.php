<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * verify credentials and return a token jwt
     *
     * @param  array $credentials
     * @return string
     */
    public function attemptLogin(array $credentials): string
    {
        if (! $token = Auth::guard('api')->attempt($credentials)){
            throw new AuthenticationException('The credentials provided are incorrect.');
        }

        return $token;
    }
}
