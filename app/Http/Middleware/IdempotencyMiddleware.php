<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class IdempotencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         //X-Idempotency-Key =  idempotency_4e5e0637-a481-4299-9c58-50378117e860



        // 1- get idempotency key from header
        $idempotencyKey = $request->header('X-Idempotency-Key');   

        // 2- if no key-> proced with controler logic.
        if(!$idempotencyKey)
        {
            return $next($request);
        }

       
        // 3 - if key exists in cache -> return cached response.
        if(Cache::has("idempotency_{$idempotencyKey}"))
        {
            $cacheResponse = Cache::get("idempotency_{$idempotencyKey}");
            return response()->json($cacheResponse->original ? $cacheResponse->original : $cacheResponse ,200 , ['x-cache' => 'HIT-IDENTICAL']);
        }

        // 4 - if key not exists in cache -> proced with controler logic.

        $response = $next($request);
        
        if($response->isSuccessful())
        {
            Cache::put("idempotency_{$idempotencyKey}", $response, now()->addHours(10));
        }


        // 5 - return response.
        return $response;
    }
}
