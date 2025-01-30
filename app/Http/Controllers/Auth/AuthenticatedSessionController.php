<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        // Obtener las credenciales desde el request
        $credentials = $request->only('email', 'password');
        
        // Intentar la autenticación tradicional
        $request->authenticate();
        
        // Regenerar la sesión después de la autenticación para evitar el secuestro de sesión
        $request->session()->regenerate();
        
        // Luego generamos el token JWT para la autenticación API
        try {
            // Verificar si las credenciales son válidas con JWT
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
        } catch (JWTException $e) {
            // En caso de error al generar el token
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
        
        // Ahora el usuario está autenticado tanto por la sesión como por el JWT.
        return response()->json([
            'token' => $token, // El token JWT generado
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Get the user details from the JWT token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserFromToken(Request $request)
    {
        try {
            // Obtener el token desde el header de autorización o desde el cuerpo de la solicitud
            $token = $request->bearerToken();
            
            // Verificar si el token es válido y obtener el usuario
            if ($user = JWTAuth::authenticate($token)) {
                return response()->json([
                    'user' => $user
                ], 200);
            }
            
            // Si no se encuentra el usuario
            return response()->json(['error' => 'Usuario no encontrado o token inválido'], 401);
        } catch (JWTException $e) {
            // En caso de error al autenticar el token
            return response()->json(['error' => 'No se pudo procesar el token'], 500);
        }
    }
}
