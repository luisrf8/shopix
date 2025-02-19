<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'http://192.168.1.103:8000',
        'http://192.168.1.103:8000/api/*',
        'api/*', // Excluir todas las rutas dentro del grupo 'api' de la verificación CSRF
        'login', // Excluir la ruta 'login' de la verificación CSRF
        'logout', // Excluir la ruta 'logout' de la verificación CSRF
    ];
}
