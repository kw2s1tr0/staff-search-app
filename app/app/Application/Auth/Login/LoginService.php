<?php

namespace App\Application\Auth\Login;

use App\Application\Auth\Login\Exception\AuthenticationFailedException;
use App\Application\Auth\Login\Exception\LoginRateLimitedException;
use App\Application\Auth\Login\Input\LoginInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

final class LoginService
{
    public function execute(LoginInput $input): void
    {
        $throttleKey = $this->buildThrottleKey($input);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $availableInSeconds = RateLimiter::availableIn($throttleKey);

            throw new LoginRateLimitedException($availableInSeconds);
        }

        $credentials = [
            'email' => $input->email,
            'password' => $input->password,
        ];

        if (! Auth::attempt($credentials, $input->remember)) {
            RateLimiter::hit($throttleKey);

            // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
            throw new AuthenticationFailedException;
        }

        RateLimiter::clear($throttleKey);
    }

    private function buildThrottleKey(LoginInput $input): string
    {
        $lowercaseEmail = Str::lower($input->email);
        $email = Str::transliterate($lowercaseEmail);

        return $email.'|'.$input->ipAddress;
    }
}
