<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RedirectIfApiAuthenticated extends BaseMiddleware
{
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
        if($guard != null){ 
            auth()->shouldUse($guard);
            try 
            {
                $user = JWTAuth::parseToken()->authenticate();
                  
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json([
                        //'token_status' =>0,
                    'message' => 'Token is Invalid']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json([//'token_status' =>1,
                    'message' => 'Token is Expired']);
                }else{
                    return response()->json([//'token_status' =>2,
                    'message' => 'Authorization Token not found']);
                }
            }
            return $next($request);
        }
        return $this->unauthorized();
     
    }
    
  
    
    
    
    
    
    
    
    
    
    
}
