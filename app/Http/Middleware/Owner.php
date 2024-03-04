<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(
            !auth()->check() &&
            request()->route('user_id') &&
            auth()->user()->id !== intval(request()->route('user_id'))
        )
        {
            abort(403);
        }

        return $next($request);
    }
}
