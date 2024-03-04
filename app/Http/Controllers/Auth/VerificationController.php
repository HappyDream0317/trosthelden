<?php

namespace App\Http\Controllers\Auth;

use App\SendinBlue\SendinBlueHandler;
use App\SendinBlue\SendinBlueTracker;
use App\Http\Controllers\Controller;
use App\Mail\IntroductionEmail;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if ($request->route('id') != $user->getKey()) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $this->eventNewRegistration($user);
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect($this->redirectPath())->with('verified', true);
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user('api');

        if($user->is_business_account) {
            $user->sendCompanyEmailVerificationNotification();
        } else {
            $user->sendEmailVerificationNotification();
        }

        return response()->json([]);
    }

    public function resendChangeEmailVerification(Request $request)
    {
        $request->user('api')->sendChangeEmailNotification();

        return response()->json([]);
    }

    protected  function eventNewRegistration(User $user){
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitNewRegistration();
    }

}
