<?php

namespace Tests\Unit\Application\Auth\Login\Input\Builder;

use App\Application\Auth\Login\Input\Builder\LoginInputBuilder;
use PHPUnit\Framework\TestCase;

class LoginInputBuilderTest extends TestCase
{
    public function test_it_builds_login_input_from_validated_values(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new LoginInputBuilder;

        $input = $builder->build([
            'email' => 'test@example.com',
            'password' => 'password',
            'remember' => true,
        ], '192.0.2.1');

        $this->assertSame('test@example.com', $input->email);
        $this->assertSame('password', $input->password);
        $this->assertTrue($input->remember);
        $this->assertSame('192.0.2.1', $input->ipAddress);
    }

    public function test_it_uses_defaults_for_optional_values(): void
    {
        // phpcs:ignore PSR12.Classes.ClassInstantiation.MissingParentheses
        $builder = new LoginInputBuilder;

        $input = $builder->build([
            'email' => 'test@example.com',
            'password' => 'password',
        ], null);

        $this->assertFalse($input->remember);
        $this->assertSame('', $input->ipAddress);
    }
}
