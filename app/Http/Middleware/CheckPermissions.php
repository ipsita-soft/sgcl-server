<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = auth()->user();
        $rolePermissions = json_decode($user->role->permissions);
        
        foreach ($permissions as $permission) {
            if (in_array($permission, $rolePermissions)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Forbidden',
            'status' => false,
            'data' => '',
        ], 403);
    }
}
