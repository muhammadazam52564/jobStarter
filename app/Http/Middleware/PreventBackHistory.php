<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
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
        return $next($request);
        return $response->header('cache-control', 'nocache','no-store', 'max-age=0', 'must-revalidate')
        ->header('pragma', 'no-cache')
        ->header('Expires', 'sun, 02 Jan 1990 00:00:00 GMT');
    }
}
