<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Asegurar que la respuesta sea JSON agregando Accept header
        if (!$request->acceptsJson()) {
            $request->headers->set('Accept', 'application/json');
        }

        $response = $next($request);

        // Si no tiene content-type o es HTML, intentar convertir a JSON
        $contentType = $response->headers->get('Content-Type') ?? '';
        if (strpos($contentType, 'application/json') === false &&
            strpos($contentType, 'text/html') !== false) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}
