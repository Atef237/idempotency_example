<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class IdempotencyMiddleware
{
    /**
     * This is a simple example of idempotency middleware. You can improve it by adding more features based on your case
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         //X-Idempotency-Key =  idempotency_4e5e0637-a481-4299-9c58-50378117e860



        ##################### 1- get idempotency key from header #####################
        $idempotencyKey = $request->header('X-Idempotency-Key');   

        ##################### 2- if no key-> proced with controler logic. #####################
        if(!$idempotencyKey)
        {
            return $next($request);
        }

        ##################### 3- if key not exists in cache -> proced with controler logic. #####################

        // 3.1 - save response in variable(response) to check if it is successful.
        $response = $next($request);
        
        // 3.2 - if response is successful -> save response in cache and named it idempotency_$idempotencyKey and add 10 hours to it.
        if($response->isSuccessful())
        {
            Cache::put("idempotency_{$idempotencyKey}", $response, now()->addHours(10));
        }

       
        ##################### 4- if key exists in cache -> return cached response. #####################
        if(Cache::has("idempotency_{$idempotencyKey}"))
        {
            // 4.1 - get cached response.
            $cacheResponse = Cache::get("idempotency_{$idempotencyKey}");

            // 4.2 - return original response from cached and add x-cache header to notify client that response is cached.
            return response()->json($cacheResponse->original ? $cacheResponse->original : $cacheResponse ,200 , ['x-cache' => 'HIT-IDENTICAL']);
        }

        ##################### 5- return response. #####################
        return $response;
    }
}
