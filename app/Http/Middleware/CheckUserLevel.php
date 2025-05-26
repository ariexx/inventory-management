<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserLevel
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        if (!$request->user() || (count($levels) > 0 && !in_array($request->user()->level, $levels))) {
            // Redirect based on user level if they're trying to access unauthorized area
            if ($request->user()) {
                return redirect()->route($this->getDefaultRouteForLevel($request->user()->level));
            }

            return redirect()->route('login');
        }

        return $next($request);
    }

    /**
     * Get the default route for a user level.
     */
    private function getDefaultRouteForLevel(string $level): string
    {
        return match($level) {
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'staff' => 'staff.dashboard',
            default => 'dashboard',
        };
    }
}
