<?php
namespace App\Http\Middleware;
use Closure;
class PreflightResponse
{
    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Type', 'text/plain');

        if ($request->isMethod('OPTIONS')) {
            return response('');
        }

        return $response;
    }
 }