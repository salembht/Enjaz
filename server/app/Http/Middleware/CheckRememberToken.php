<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
class CheckRememberToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasCookie('remember_token')) {
            $token = $request->cookie('remember_token');
            $tokenModel = PersonalAccessToken::findToken($token);
            
            if ($tokenModel && !$request->user()) {
                $user = $tokenModel->tokenable;
                auth()->login($user);
            }
        }

        return $next($request);
    }
}
