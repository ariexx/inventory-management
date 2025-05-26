<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class LoginService
{
    /**
     * Attempt to authenticate a user and handle the login process
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Update last login timestamp
            $user->last_login_at = Carbon::now();
            $user->save();

            $request->session()->regenerate();

            return [
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'redirect' => $this->getRedirectPath($user)
            ];
        }

        return [
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ];
    }

    /**
     * Get the post-login redirect path for the user
     *
     * @param User $user
     * @return string
     */
    protected function getRedirectPath(User $user): string
    {
        // You can implement role-based redirect logic here
        // For example, redirect admins to dashboard and regular users elsewhere
        return '/dashboard';
    }

    /**
     * Logout the currently authenticated user
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
