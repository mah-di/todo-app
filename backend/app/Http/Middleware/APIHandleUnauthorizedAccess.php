<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class APIHandleUnauthorizedAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isClientError() && !isset($response->original['code'])) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }

        return $response;
    }
}
