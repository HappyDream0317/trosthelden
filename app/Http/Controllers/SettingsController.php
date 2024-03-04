<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AccessTokensController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getMe(Request $request)
    {
        return response()->json($request->user('api')->makeVisible(['email', 'lastname', 'firstname', 'nickname', 'cancellation_at']));
    }

    /***
     * @param $tokenId
     */
    protected function revokeAccessTokens($tokenId)
    {
        /**
         * @var $tokenRepository TokenRepository
        */

        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($tokenId);
    }

    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function sendResponseWithNewAccessTokens(Request $request)
    {
        $response = AccessTokensController::refreshAccessToken($request);
        if ($response->isSuccessful()) {
            $content =  ['status' => 'success'];
            $data = json_decode($response->getContent());
            return AccessTokensController::sendResponseWithAccessTokens($content, $response->getStatusCode(), $data->access_token, $data->refresh_token);
        }

        return response()->json(['status' => 'failed']);
    }

    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function changeEmail(Request $request)
    {
        // validate the email
        $request->validate([
            'new-email' => 'required|email|confirmed',
        ]);

        // prev != new
        if (strcmp($request->user('api')->email, $request->get('new-email')) == 0) {
            return response()->json(["error" => "Deine neue E-Mail-Adresse kann nicht die selbe wie vorher sein. Bitte ändere deine E-Mail."]);
        }

        $email = DB::table('users')->where('email', $request->get('new-email'))->first();

        if ($email) {
            return response()->json(["error" => "Diese E-Mail-Adresse gibt es bereits in unserem System. Hast du vielleicht schon einen Account?"]);
        }

        /**
         * @var User $user
         * @var AccessTokensController $accessTokenController
        */

        // change the email
        $user = Auth::user();
        $user->email = $request->get('new-email');
        $user->email_verified_at = null;
        $user->save();
        $request->user('api')->sendChangeEmailNotification();

        // revoke all access and refresh tokens
        $request->user('api')
          ->tokens
          ->each(function ($token, $key) {
              $this->revokeAccessTokens($token->id);
          });

        return $this->sendResponseWithNewAccessTokens($request);
    }

    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        // validate the passwords
        $request->validate([
          'current-password' => 'required',
          'new-password' => 'required|string|min:6|confirmed',
      ]);

        // check the current password
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return response()->json(["error" => "Your current password does not matches with the password you provided. Please try again."]);
        }

        // prev != new
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return response()->json(["error" => "New password cannot be same as your current password. Please choose a different password."]);
        }

        // change the password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));

        // revoke all access and refresh tokens
        $request->user('api')
          ->tokens
          ->each(function ($token, $key) {
              $this->revokeAccessTokens($token->id);
          });

        // all good
        if ($user->save()) {
            return $this->sendResponseWithNewAccessTokens($request);
        }

        return response()->json(['status' => 'failed']);
    }

    public function changeNickname(Request $request)
    {
        // validate the nickname
        $request->validate([
            'new-nickname' => 'required',
        ]);

        // prev != new
        if (strcmp($request->user('api')->nickname, $request->get('new-nickname')) == 0) {
            return response()->json(["error" => "Dein neuer Benutzername kann nicht der selbe wie vorher sein. Bitte ändere deinen Nutzernamen."]);
        }

        $nickname = DB::table('users')->where('nickname', $request->get('new-nickname'))->first();

        if ($nickname) {
            return response()->json(["error" => "Diesen Benutzernamen gibt es bereits in unserem System. Bitte wähle einen anderen."]);
        }

        // change the nickname
        $user = Auth::user();
        $user->nickname = $request->get('new-nickname');
        $user->save();

        return response()->json(['status' => $user->save()]);
    }
}
