<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, ...$role)
    {
        // Check if the session has a role
        if (!session()->has('role')) {
            return redirect()->route('login')->with('error', 'Login terlebih dahulu!');
        }

        // Get the current user's role
        $userRole = session('role');

        // Check if the user's role is authorized to access the page
        if (!in_array($userRole, $role)) {
            return redirect('/');
        }

        // Proceed to the next request if authorized
        return $next($request);
    }

}
