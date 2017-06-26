<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;
use Firebase\JWT\JWT;
use \Exception;

class CheckUserTokenIsValid
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
        try {
            $token = JWT::decode($request->access_token, env('APP_KEY'), array('HS256'));
            $user = User::find($token['id']);
            if (empty($user)) {
                throw new Exception('User not found');
            }
            return $next($request);
        } catch (Exception $e) {
            $headers = array('Content-Type' => 'application/json; charset=utf-8');
            $content = array(
                'status' => 500,
                'error' => "Vous avez été déconnecté",
                'data' => array(
                    'invalid_token' => true
                )
            );
            $response = response()->json($content, 500, $headers, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            return $response;
        }
    }
}
