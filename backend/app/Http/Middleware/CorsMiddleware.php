<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            //'Access-Control-Max-Age' => '86400',
        ];

        $allowedContentTypes = [
            'Content-Type' => 'text/plain',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response('', 204)->withHeaders($headers,$allowedContentTypes);
        }

        return $next($request)->withHeaders($headers);
    }
}
