<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyAccessToken as Middleware;

class VerifyAccessToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el token de acceso está presente en los encabezados de la solicitud
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Access Token missing'], 401);
        }

        // Extraer el token de acceso de los encabezados de la solicitud
        $accessToken = explode(' ', $request->header('Authorization'))[1];

        // Verificar la validez del token de acceso
        // if (!Auth::guard('sanctum')->check()) {
        //     return response()->json(['error' => 'Invalid Access Token'], 401);
        // }

        // Continuar con la solicitud si el token de acceso es válido
        return $next($request);
    }
}
