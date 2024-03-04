<?php

namespace App\Http\Controllers\B2B;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerificationController extends Controller
{

    public function verify(Request $request)
    {
        $user_id = $request->get('user_id');
        $hash = $request->get('hash');

        $user = User::findOrFail($user_id);

        abort_if($user_id != $user->getKey(), 404, "User ID not found");

        abort_if(!hash_equals((string)$hash, sha1($user->getEmailForVerification())), 404, 'Hash not valid');

        return response()->json(['success' => true]);
    }

    public function resend(Request $request)
    {
        $user_id = $request->get('user_id');


        abort_if(!$user_id, 404, "User ID not found");

        $user = User::findOrFail($user_id);

        abort_if(!$user, 404, "User not found");

        if ($user->save()) {

            $user->sendCompanyEmailVerificationNotification();

            return response()->json(['success' => true]);
        }

        abort(404, "User can't reset password");

    }

    public function reset(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'hash' => 'required',
            'password' => 'required|min:8|required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'required|min:8',
        ]);

        $user_id = $request->get('user_id');
        $hash = $request->get('hash');
        $password = $request->get('password');

        $user = User::findOrFail($user_id);

        abort_if($user_id != $user->getKey(), 404, "User ID not found");

        abort_if(!hash_equals((string)$hash, sha1($user->getEmailForVerification())), 404, 'Hash not valid');

        $user->password = Hash::make($password);
        $user->email_verified_at = now();

        if ($user->save()) {

            return response()->json([
                'success' => true,
                'email' => $user->email,

            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

}