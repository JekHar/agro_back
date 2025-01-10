<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    private $exceptionsPatterns = [
        'dashboard',
    ];

    private function getUserPermissions($userId): array
    {
        $cacheKey = 'user_permissions_'.$userId;

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($userId) {
            $user = \App\Models\User::findOrFail($userId);

            return $user->permissions->pluck('name')->toArray();
        });
    }

    private function isExceptedRoute(?string $routeName): bool
    {
        if (is_null($routeName)) {
            return true;
        }

        foreach ($this->exceptionsPatterns as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    private function userHasPermission($userId, $routeName): bool
    {
        $userPermissions = $this->getUserPermissions($userId);

        return in_array($routeName, $userPermissions);
    }

    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $routeName = $route->getName();

        if ($this->isExceptedRoute($routeName)) {
            return $next($request);
        }

        if ($this->userHasPermission(Auth::id(), $routeName)) {
            throw new UnauthorizedException(403, trans('error.permission').' <b>'.$routeName.'</b>');
        }

        return $next($request);
    }
}
