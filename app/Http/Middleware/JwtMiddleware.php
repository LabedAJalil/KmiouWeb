<?php

 namespace App\Http\Middleware;

    use Closure;
    use JWTAuth;
    use Exception;
    use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

    class JwtMiddleware extends BaseMiddleware
    {

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (Exception $e) { 
                
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                   return response()->json(['success' => 0,'msg' => 'Token is Invalid','result'=> []]);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['success' => 0, 'msg' => 'Authorization token not found','result' =>[]]);
                }else{
                    return response()->json(['success' => 0, 'msg' => 'Please Log out and Login again','result' =>[]]);
                }
            }
            return $next($request);
        }
    }