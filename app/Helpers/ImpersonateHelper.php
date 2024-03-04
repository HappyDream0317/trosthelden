<?php


namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use trosthelden\NovaImpersonate\Cookies;

class ImpersonateHelper
{
    public static function revokeImpersonateTokenFromCookie()
    {
        if (Cookie::has(Cookies::IMPERSONATING)) {
            $token = Cookie::get(Cookies::IMPERSONATING);
            $tokenId = app(Parser::class)->parse($token)->claims()->get('jti');
            $tokenRepository = app(TokenRepository::class);
            $tokenRepository->revokeAccessToken($tokenId);
        }
    }
}
