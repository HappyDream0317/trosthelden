<?php

namespace App\Http\Middleware;

use App\Helpers\CookieHelper;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use trosthelden\NovaImpersonate\Cookies;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        Cookies::IMPERSONATING
    ];
}
