<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        // Validación de las credenciales
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Crear el token de acceso para el usuario
            $token = $user->createToken('auth_token', ['role' => $user->role])->plainTextToken;

            // Devolver el token y el rol del usuario
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
            ]);
        }

        // Si las credenciales son incorrectas
        return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
    }
}
