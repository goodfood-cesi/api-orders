<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        try {
            if (!$request->hasHeader('Authorization')) throw new Exception('Unauthorized');
            if (empty($request->header('Authorization'))) throw new Exception('Unauthorized');
            $jwt = str_replace('Bearer ', '', $request->header('Authorization'));

            $key = new Key(file_get_contents(__DIR__ .'/../../../storage/public.pem'), 'RS256');
            $token = JWT::decode($jwt, $key);

        } catch (Exception $e) {
            $response = new Response();
            $response->setContent([
                'result' => 'error',
                'message' => $e->getMessage()
            ]);
            return $response->header('Content-Type', 'application/json')->setStatusCode(401, "Unauthorized");
        }

        return $next($request->merge(['token' => $token]));
    }
}
