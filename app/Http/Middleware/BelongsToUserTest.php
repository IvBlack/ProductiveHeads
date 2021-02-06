<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BelongsToUserTest
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
        if (
            !$request->route()->hasParameter('userTest')
            || +$request->route()->parameter('userTest')->user_id !== +$user->id
        ) {
            return response()->json(['message' => 'Unauthenticated. You has no access to test'], 401);
        }
        return $next($request);
    }
}
