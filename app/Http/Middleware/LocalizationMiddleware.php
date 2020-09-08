<?php

namespace App\Http\Middleware;

use Closure;

class LocalizationMiddleware
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
        //Check header request and set language defaut
        $lang = ($request->hasHeader('X-localization')) ? $request->header('X-localization') : 'vi';
        //Set laravel localization
        \App::setLocale($lang);

        //Continue request
        return $next($request);
    }
}
