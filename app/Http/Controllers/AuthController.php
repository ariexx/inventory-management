<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $loginService;

    /**
     * Create a new controller instance.
     *
     * @param LoginService $loginService
     */
    public function __construct(
        LoginService $loginService,
    )
    {
        $this->loginService = $loginService;
    }

    /**
     * Show the login form
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('login');
    }

    /**
     * Handle the login attempt
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $result = $this->loginService->login($request);

        if ($result['success']) {
            return redirect()->intended($result['redirect']);
        }

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => $result['message']]);
    }

    /**
     * Log the user out
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->loginService->logout($request);

        return redirect()->route('login');
    }
}
