<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\B2BPartner;
use Illuminate\Support\Facades\Redirect;

class VerifyAffiliate
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
        $attr = $request->query('partner');

        if (!$attr) {
            return $next($request);
        } else {

            $partner = B2BPartner::where('code', $attr)->first();

            if (!$partner) {
                return redirect()->to($request->fullUrlWithoutQuery('partner'));
            } else {
                return $next($request);
            }
        }
    }
}
