<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserAuth
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
        $token = $request->bearerToken();
        if ($token) {
            $user = User::getByAccessToken($token);
            if ($user) {
                $user->loginUser();
                $request->user = $user;
                return $next($request);
            }
        }
        return response(["message" => "Permission Denied"], 401);
    }
}
