<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ValidatorHelper;
use App\Http\Controllers\Controller;
use App\SendinBlue\SendinBlueHandler;
use App\User;
use App\B2BPartner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\RegisteredCompany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'nickname.required' => 'Der Benutzername ist erforderlich.',
            'nickname.max' => 'Dieser Benutzername muss maximal :max Zeichen lang sein.',
            'nickname.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'email.email' => 'E-Mail hat ein ungültiges Format.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits registriert. Solltest du dein Passwort nicht mehr wissen, kannst es dir über "Passwort vergessen" ein neues Passwort erstellen.',
            'password.required' => 'Die Passwort ist erforderlich.',
            'password.min' => 'Bitte wähle ein sicheres Passwort aus. Das Passwort muss mindestens 8 Zeichen lang sein und mindestens einen Buchstaben sowie eine Zahl beinhalten.',
        ];

        $rules = [
            'nickname' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        
        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
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

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $check = $this->validator($request->all())->validate();

        [$passwordErrorMessage, $valid] = ValidatorHelper::password($request->get('password'));
        if (!$valid) {
            return response()->json([
                'errors' => [
                    "password" => $passwordErrorMessage
                ]
            ], 400);
        }

        if ($check) {
            $user = $this->create($request->all());
            event(new Registered($user));

            $this->sendinBlueCreateContact($user);
        }



        return response()->json([]); // TODO: Generate Auth and login
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    protected function sendinBlueCreateContact(User $user)
    {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->createContact();
    }
    
    
    protected function getB2BPartnerId ($code) {
        $b2bPartnerId = null;

        if (!empty($code)) {
            $partner = B2BPartner::where('code', $code)->first();
            if($partner) $b2bPartnerId = $partner->id;
        }
        
        return $b2bPartnerId;
    }

}
