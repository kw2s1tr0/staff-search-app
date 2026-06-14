<?php

namespace App\Http\Controllers\Web\Auth;

use App\Application\Auth\Login\Input\Builder\LoginInputBuilder;
use App\Application\Auth\Login\LoginService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Auth\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly LoginInputBuilder $loginInputBuilder,
        private readonly LoginService $loginService,
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $ipAddress = $request->ip();
        $input = $this->loginInputBuilder->build($validated, $ipAddress);

        $this->loginService->execute($input);
        $request->session()->regenerate();

        $employeeIndexUrl = route('employees.index', absolute: false);

        return redirect()->intended($employeeIndexUrl);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
