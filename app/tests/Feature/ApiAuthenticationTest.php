<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_issue_a_read_only_api_token(): void
    {
        $this->travelTo(CarbonImmutable::parse('2026-06-20 12:00:00'));
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'token_name' => 'agent-cli',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('expires_at', '2026-07-20T12:00:00+00:00');

        $plainTextToken = $response->json('access_token');
        $this->assertIsString($plainTextToken);

        $accessToken = PersonalAccessToken::findToken($plainTextToken);
        $this->assertNotNull($accessToken);
        $this->assertSame('agent-cli', $accessToken->name);
        $this->assertSame(['api:read'], $accessToken->abilities);
        $this->assertTrue($accessToken->expires_at?->equalTo('2026-07-20 12:00:00'));
        $this->assertNotSame($plainTextToken, $accessToken->token);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'incorrect-password',
            'token_name' => 'agent-cli',
        ]);

        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'メールアドレスまたはパスワードが正しくありません。');
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_input_is_validated(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => '',
            'token_name' => '',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password', 'token_name']);
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        $user = User::factory()->create();
        $credentials = [
            'email' => $user->email,
            'password' => 'incorrect-password',
            'token_name' => 'agent-cli',
        ];

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/api/auth/login', $credentials)->assertUnauthorized();
        }

        $response = $this->postJson('/api/auth/login', $credentials);

        $response
            ->assertTooManyRequests()
            ->assertHeader('Retry-After')
            ->assertJsonStructure(['message', 'retry_after']);
    }

    public function test_authenticated_user_can_view_their_identity(): void
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('agent-cli', ['api:read'], now()->addDays(30));

        $response = $this
            ->withToken($accessToken->plainTextToken)
            ->getJson('/api/auth/me');

        $response
            ->assertOk()
            ->assertExactJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    public function test_protected_api_rejects_a_missing_token(): void
    {
        $this->getJson('/api/employees')->assertUnauthorized();
    }

    public function test_protected_api_rejects_a_token_without_read_ability(): void
    {
        $user = User::factory()->create();
        $accessToken = $user->createToken('no-read-access', [], now()->addDays(30));

        $this
            ->withToken($accessToken->plainTextToken)
            ->getJson('/api/employees')
            ->assertForbidden();
    }

    public function test_protected_api_rejects_an_expired_token(): void
    {
        $user = User::factory()->create();
        $expiresAt = CarbonImmutable::now()->addMinute();
        $accessToken = $user->createToken('expired-token', ['api:read'], $expiresAt);
        $afterExpiration = $expiresAt->addSecond();
        $this->travelTo($afterExpiration);

        $this
            ->withToken($accessToken->plainTextToken)
            ->getJson('/api/employees')
            ->assertUnauthorized();
    }

    public function test_logout_revokes_only_the_current_token(): void
    {
        $user = User::factory()->create();
        $expiresAt = now()->addDays(30);
        $currentToken = $user->createToken('current-cli', ['api:read'], $expiresAt);
        $otherToken = $user->createToken('other-cli', ['api:read'], $expiresAt);

        $this
            ->withToken($currentToken->plainTextToken)
            ->postJson('/api/auth/logout')
            ->assertNoContent();

        $this->assertNull(PersonalAccessToken::findToken($currentToken->plainTextToken));
        $this->assertNotNull(PersonalAccessToken::findToken($otherToken->plainTextToken));

        Auth::forgetGuards();
        $this
            ->withToken($currentToken->plainTextToken)
            ->getJson('/api/auth/me')
            ->assertUnauthorized();

        Auth::forgetGuards();
        $this
            ->withToken($otherToken->plainTextToken)
            ->getJson('/api/auth/me')
            ->assertOk();
    }
}
