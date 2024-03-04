<?php

namespace App\Listeners;

use App\Helpers\ImpersonateHelper;
use App\Http\Controllers\Auth\AccessTokensController;
use App\User;
use Illuminate\Support\Facades\Cookie;
use trosthelden\NovaImpersonate\Cookies;

class SetImpersonateCookie
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

        $user = User::find($event->impersonated->id);
        $token = $user->createToken('impersonation_token', ['*']);
        $cookie = AccessTokensController::makeCookie(Cookies::IMPERSONATING, $token->accessToken);
        Cookie::queue($cookie);
    }
}
