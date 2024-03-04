<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CookieHelper;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use App\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie as LaravelCookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;
use trosthelden\NovaImpersonate\Cookies;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class AccessTokensController extends Controller
{
    use ThrottlesLogins;

    protected User $userInfo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api'])->except(['store', 'remote', 'update']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
        'username' => 'required|string',
        'password' => 'required|string',
    ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    private function setUserInfo($username)
    {
        $user = User::where($this->username(), $username)->first();
        $user->permissionsViaRoles = $user->getPermissionsViaRoles()->pluck('name');
        $this->userInfo = $user;
    }

    private function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Generate a new access token.
     *
     * @param Request $request
     * @return RedirectResponse|Response|JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        return $this->requestPasswordGrant($request);
    }

    /**
     * Refresh access token controller access
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $response = self::refreshAccessToken($request);

        if ($response->isSuccessful()) {
            return $this->sendSuccessResponse($response);
        }

        return response($response->getContent(), $response->getStatusCode());
    }

    /**
     * Generate a new access token from remote auth.
     *
     * @param Request $request
     * @return RedirectResponse|Response|JsonResponse
     * @throws ValidationException
     */
    public function remote(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('remote_access_token', $request->token)->first();

        if (!$user || $user->id !== $request->id) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_FORBIDDEN);
        } else {

            $request->request->add([
                'username' => $user->email,
                'password' => 'password',
            ]);

            Cache::put('remote_login', true, $seconds = 120);

            $this->deleteUserRemoteAccessToken($user);

            return $this->requestPasswordGrant($request);
        }
    }

    /**
     * Refresh an access token.
     *
     * @param Request $request
     * @return Response
     */
    public static function refreshAccessToken(Request $request)
    {
        $token = $request->cookie(CookieHelper::REFRESH_TOKEN_COOKIE);
        if (!$token) {
            throw ValidationException::withMessages([
                'refresh_token' => trans('oauth.missing_refresh_token'),
            ]);
        }

        $proxyRequest = Request::create('/oauth/token', 'POST', [
            'client_id' => config('auth.proxy.client_id'),
            'client_secret' => config('auth.proxy.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $token,
            'scopes' => '[*]',
        ]);

        return app()->handle($proxyRequest);
    }

    public function destroy(Request $request)
    {
        return $this->revoke($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function revoke(Request $request)
    {
        $user = $request->user() ?? $request->user('api');
        $user->token()->revoke();
        $access_cookie = LaravelCookie::forget(CookieHelper::ACCESS_TOKEN_COOKIE);
        $refresh_cookie = LaravelCookie::forget(CookieHelper::REFRESH_TOKEN_COOKIE);
        $impersonate_cookie = LaravelCookie::forget(Cookies::IMPERSONATING);

        return response()->json([
            'message' => 'successful-logout'
        ])->withCookie($access_cookie)->withCookie($refresh_cookie)->withCookie($impersonate_cookie);
    }

    /**
     * Create a new access token from a password grant client.
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws \Exception
     */
    public function requestPasswordGrant(Request $request)
    {
        $proxyRequest = Request::create('/oauth/token', 'POST', [
            'client_id' => config('auth.proxy.client_id'),
            'client_secret' => config('auth.proxy.client_secret'),
            'grant_type' => config('auth.proxy.grant_type'),
            'username' => $request->username,
            'password' => $request->password,
            'scopes' => '',
        ]);

        $response = app()->handle($proxyRequest);

        if ($response->isSuccessful()) {
            $this->clearLoginAttempts($request);
            $this->setUserInfo($request->username);

            EventService::notify(
                $this->userInfo,
                EventService::LOGIN_EVENT
            );

            return $this->sendSuccessResponse($response);
        }

        $this->incrementLoginAttempts($request);

        return response($response->getContent(), $response->getStatusCode());
    }

    /**
     * Return a successful response for requesting an api token.
     *
     * @param Response $response
     * @return Response
     */
    public function sendSuccessResponse(Response $response)
    {
        $data = json_decode($response->getContent());
        $content = [
            'user' => $this->getUserInfo(),
        ];

        return self::sendResponseWithAccessTokens($content, $response->getStatusCode(), $data->access_token, $data->refresh_token);
    }

    /**
     * Attach to the response the access and refresh tokens
     *
     * @param array $content
     * @param int $responseStatusCode
     * @param string $accessToken
     * @param string|null $refreshToken
     *
     * @return Response
    */
    public static function sendResponseWithAccessTokens(array $content, int $responseStatusCode, string $accessToken, string $refreshToken = null)
    {
        $access_cookie = self::makeCookie('access_token', $accessToken, 24 * 60);
        $response = response($content, $responseStatusCode)->cookie($access_cookie);

        if ($refreshToken !== null) {
            $refresh_cookie = self::makeCookie('refresh_token', $refreshToken, 10 * 24 * 60);
            $response = $response->cookie($refresh_cookie);
        }

        return $response;
    }

    public function hasUserVerifiedEmail(Request $request)
    {
        $user = User::where($this->username(), $request->username)->first();
        return $user->hasVerifiedEmail();
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendFailedEmailVerificationLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
        $this->username() => [__('Ein erneuter Login ist nicht möglich, solange die E-Mail-Adresse nicht bestätigt wurde')],
    ])
        ->status(Response::HTTP_FORBIDDEN);
    }

    public static function makeCookie(string $name, string $token, int $expire = 1440)
    {
        return new SymfonyCookie(
            $name,
            $token,
            now()->addMinutes($expire)->toDateString(),
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            config('session.http_only'),
            true,
            App::environment('local') ? 'lax' : 'none',
        );
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Remove User remote access token.
     *
     * @param User $user
     */
    public function deleteUserRemoteAccessToken(User $user)
    {
        $user->remote_access_token = null;
        $user->save();
    }
}
