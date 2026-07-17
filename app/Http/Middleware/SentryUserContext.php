<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentry\State\Scope;
use function Sentry\configureScope;

class SentryUserContext
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            configureScope(function (Scope $scope): void {
                $user = auth()->user();

                $scope->setUser([
                    'id'       => $user->id_user,
                    'username' => $user->username,
                    'role'     => $user->role,
                ]);
            });
        }

        return $next($request);
    }
}