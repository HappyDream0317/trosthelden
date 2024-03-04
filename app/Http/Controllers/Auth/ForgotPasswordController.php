<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = \App\User::where('email', $request->email)->first();

        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            $user->sendEmailForgotPasswordNotification($token);
            return response()->json([
                'status' => true,
            ]);
        }
        return response()->json([
            'status' => false,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'hash' => 'required',
            'password' => 'required|min:8|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'required|min:8',
        ]);

        $success = false;
        $dbToken = DB::table('password_resets')->where('email', $request->email)->first();

        if ($dbToken && Hash::check($request->hash, $dbToken->token)) {
            $user = \App\User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);

            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();
            $success = true;
        }

        return response()->json([
            'success' => $success,
        ]);
    }
}
