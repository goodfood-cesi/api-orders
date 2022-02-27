<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
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

            $token = JWT::decode($jwt, $_ENV['JWT_PUBLIC_KEY'], ['RS256']);

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
