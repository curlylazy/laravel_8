<?php

namespace App\Http\Middleware;

use Closure;

use App\Http\Controllers\aAuth;

class CekLogin
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
        $kodeuser = $request->session()->get('kodeuser');

        if(empty($kodeuser))
        {
            return redirect()->action([aAuth::class, 'login']);
        }

        return $next($request);
    }
}

?>
