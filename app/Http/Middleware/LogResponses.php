<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogResponses
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response|\Illuminate\Http\JsonResponse $response
     */
    public function terminate(Request $request, $response)
    {
        logger()->debug('Response logged', [
            'headers' => $response->headers->all(),
            'content' => $response->content(),
        ]);
    }
}
