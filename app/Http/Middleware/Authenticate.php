<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\user;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            if ($request->has('api_token')) {
                $token = $request->input('api_token');
                $check_token = User::where('api_token', $token)->first();
                if ($check_token == null) {
                    return response(
                        array
                        (
                            "status"=>"401",
                            "error"=>"Not Authorized"
                    ), 401);
                }
            }else{
                return response(
                    array(
                        "status"=>$request,
                        "error"=>"Not Authorized"
                ), 401);
            }
        }
        return $next($request);
    }
}
