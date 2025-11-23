<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $apiKey = $request->header('x-api-key');

        if ($apiKey !== '12cdf608-c2e9-43bf-a045-00911067f9f6') {
            return response()->json(['message' => 'Unauthorized API Key'], 401);
        }

        return $next($request);
    }
}
