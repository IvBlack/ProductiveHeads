<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TestSubject
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
        if (empty($user) || !$user->hasRole('test-subject')) {
            return response()->json(['message' => 'Unauthenticated. Test subject role required'], 401);
        }
        return $next($request);
    }
}
