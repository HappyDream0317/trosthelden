<?php

namespace App\Listeners;

use App\Helpers\CookieHelper;
use App\Helpers\ImpersonateHelper;
use Illuminate\Support\Facades\Cookie as LaravelCookie;
use Illuminate\Support\Facades\Cookie;
use Laravel\Passport\TokenRepository;
use trosthelden\NovaImpersonate\Cookies;
use trosthelden\NovaImpersonate\Impersonate;

class RemoveImpersonateCookie
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        ImpersonateHelper::revokeImpersonateTokenFromCookie();
        Cookie::queue(LaravelCookie::forget(Cookies::IMPERSONATING));
    }
}
