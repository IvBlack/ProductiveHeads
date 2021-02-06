<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (empty($user) || !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthenticated. Admin role required'], 401);
        }
        return $next($request);
    }
}
