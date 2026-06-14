<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;

class AuthenticateWebRequest extends Authenticate
{
    protected function redirectTo(Request $request): string
    {
        return route('login');
    }
}
