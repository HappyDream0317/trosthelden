<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ValidatorHelper;
use App\Http\Controllers\Controller;
use App\SendinBlue\SendinBlueHandler;
use App\User;
use App\B2BPartner;
use App\SendinBlue\SendinBlueApi;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RemoteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Remote Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users and login validation
    | from Marketing site
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a rules.
     *
     * @param bool $all
     * @return array;
     */
    protected function rules($all = false)
    {
        if ($all) {
            return [
                'nickname' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];
        } else {
            return [
                'email' => 'required|string',
                'password' => 'required|string',
            ];
        }
    }


    /**
     * Get a messages.
     *
     * @return array;
     */
    protected function messages(): array
    {
        return [
            'nickname.required' => 'Der Benutzername ist erforderlich.',
            'nickname.max' => 'Dieser Benutzername muss maximal :max Zeichen lang sein.',
            'nickname.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'email.email' => 'E-Mail hat ein ungültiges Format.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits registriert. Solltest du dein Passwort nicht mehr wissen, kannst es dir über "Passwort vergessen" ein neues Passwort erstellen.',
            'password.required' => 'Die Passwort ist erforderlich.',
            'password.min' => 'Bitte wähle ein sicheres Passwort aus. Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Buchstaben sowie eine Zahl beinhalten.',
        ];
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $b2bPartnerId = $this->getB2BPartnerId($data['partner']);

        return User::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'b2b_partner_id' => $b2bPartnerId,
        ]);
    }

    protected function sendinBlueCreateContact(User $user)
    {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->createContact();
    }

    /**
     * Handle a registration request for the application of marketing.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $credentials = [
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'partner' => $request->partner
        ];

        $validator = Validator::make($credentials, $this->rules(true), $this->messages());

        if ($validator->fails()) {
            $respose = $validator->messages();

            return response()->json([
                'messages' => $respose->getMessageBag(),
                'success' => false

            ], 400);
        }

        [$passwordErrorMessage, $valid] = ValidatorHelper::password($request->get('password'));

        if (!$valid) {
            return response()->json([
                'messages' => [
                    "password" => [0 => $passwordErrorMessage]
                ],
                'success' => false
            ], 400);
        }

        $user = $this->create($credentials);
        event(new Registered($user));

        $this->sendinBlueCreateContact($user);

        $token = Str::random(60);

        $user->remote_access_token = $token;
        $user->save();

        return response()->json([
            'data' => ['id' => $user->id, 'token' => $token],
            'success' => true,
        ], 200);
    }


    /**
     * Handle a login request for the application of marketing.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $validator = Validator::make($credentials, $this->rules(false));

        if ($validator->fails()) {
            return response()->json([
                'messages' => $validator->messages()->getMessageBag(),
                'success' => false

            ], 400);
        }

        $valid = Auth::guard('web')->attempt($credentials, false, false);

        if (!$valid) {
            return response()->json([
                'messages' => [
                    "email" => [0 => 'Die angegebenen Anmeldedaten stimmen nicht mit unseren Aufzeichnungen überein']
                ],
                'success' => false
            ], 400);
        }

        $token = Str::random(60);

        $user = User::where('email', $request->email)->first();
        $user->remote_access_token = $token;
        $user->save();

        return response()->json([
            'data' => ['id' => $user->id, 'token' => $token],
            'success' => true,
        ], 200);
    }
    
    protected function getB2BPartnerId($code) {
        $b2bPartnerId = null;

        if (!empty($code)) {
            $partner = B2BPartner::where('code', $code)->first();
            if($partner) $b2bPartnerId = $partner->id;
        }
        
        return $b2bPartnerId;
    }
}
