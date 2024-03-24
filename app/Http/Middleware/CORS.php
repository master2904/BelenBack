<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CORS
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
        // header('Access-Control-Allow-Origin:*');
        // header('Access-Control-Allow-Headers:Conten-type, X-Auth-Token, Authorization, Origin');
        
        // header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
        // return $next($request)->header("Access-Control-Allow-Origin", "*")->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE")->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization"); 
        header("Access-Control-Allow-Origin", "localhost:4200");
        // header("Access-Control-Allow-Origin", "192.168.0.19");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Max-Age: 1000");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
        return $next($request);
    }
}
