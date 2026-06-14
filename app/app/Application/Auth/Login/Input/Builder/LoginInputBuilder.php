<?php

namespace App\Application\Auth\Login\Input\Builder;

use App\Application\Auth\Login\Input\LoginInput;

final class LoginInputBuilder
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function build(array $validated, ?string $ipAddress): LoginInput
    {
        $email = (string) $validated['email'];
        $password = (string) $validated['password'];
        $remember = isset($validated['remember'])
            ? (bool) $validated['remember']
            : false;
        $resolvedIpAddress = $ipAddress ?? '';

        return new LoginInput(
            email: $email,
            password: $password,
            remember: $remember,
            ipAddress: $resolvedIpAddress,
        );
    }
}
