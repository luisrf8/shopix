<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $accessToken = Str::after($request->header('Authorization'), 'Bearer ');

        $payload = explode('.', $accessToken)[1];
        $decodedPayload = base64_decode($payload);
        $tokenData = json_decode($decodedPayload);

        if (!isset($tokenData->role)) {
            return response()->json(['message' => 'Role not found in token'], 403);
        }

        $userRole = $tokenData->role;

        if (!in_array($userRole, $roles)) {
            return response()->json(['message' => 'Access Denied!'], 403);
        }

        return $next($request);
    }
}
