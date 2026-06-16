<?php

namespace App\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function attemptLogin(array $credentials): string
    {
        if (! $token = Auth::guard('api')->attempt($credentials)){
            throw new AuthenticationException('As credenciais informadas estão incorretas.');
        }

        return $token;
    }
}
