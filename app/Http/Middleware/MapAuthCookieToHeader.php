<?php


namespace App\Http\Middleware;

use App\Helpers\CookieHelper;
use Closure;
use Illuminate\Http\Request;
use trosthelden\NovaImpersonate\Cookies;

class MapAuthCookieToHeader
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Check if a impersonate cookie exists
        if ($request->hasCookie(Cookies::IMPERSONATING)) {
            //set the impersonate authorization code in the header
            $token = $request->cookie(Cookies::IMPERSONATING);
            $request->headers->add(['Authorization' => 'Bearer ' . $token]);
            return $next($request);
        }

        if (!$request->bearerToken()) {
            if ($request->hasCookie(CookieHelper::ACCESS_TOKEN_COOKIE)) {
                $token = $request->cookie(CookieHelper::ACCESS_TOKEN_COOKIE);
                $request->headers->add(['Authorization' => 'Bearer ' . $token]);
            }
        }

        return $next($request);
    }
}
