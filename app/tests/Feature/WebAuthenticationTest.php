<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class WebAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_the_login_screen(): void
    {
        $this->withoutVite();

        $response = $this->get('/login');

        $response
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSeeText('社員情報管理')
            ->assertSeeText('ログイン');
    }

    public function test_guest_is_redirected_to_login_from_employee_screen(): void
    {
        $response = $this->get('/employees', [
            'Accept' => 'text/html',
        ]);

        $response->assertRedirectToRoute('login');
    }

    public function test_user_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirectToRoute('employees.index');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_log_in_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        $user = User::factory()->create();
        $credentials = [
            'email' => $user->email,
            'password' => 'incorrect-password',
        ];

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', $credentials);
        }

        $response = $this->from('/login')->post('/login', $credentials);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertInstanceOf(ViewErrorBag::class, $errors);

        $messageBag = $errors->getBag('default');
        $message = $messageBag->first('email');

        $this->assertStringContainsString('ログイン試行回数が上限に達しました。', $message);
        $this->assertGuest();
    }

    public function test_authenticated_user_is_redirected_from_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/employees');
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirectToRoute('login');
        $this->assertGuest();
    }
}
