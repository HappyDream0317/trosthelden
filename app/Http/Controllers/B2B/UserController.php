<?php

namespace App\Http\Controllers\B2B;

use App\B2BPartner;
use App\B2BUser;
use App\Events\RegisteredCompany;
use App\Http\Controllers\Controller;
use App\Payment\BillwerkApi;
use App\SendinBlue\SendinBlueHandler;
use App\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $billwerkApi;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BillwerkApi $billwerkApi)
    {
        $this->billwerkApi = $billwerkApi;
        $this->middleware(['auth:api'])->except(['store', 'validateUser']);
    }
    
    public function store(Request $request)
    {
        $user = null;

        if ($request->userId) {
            $user = User::find($request->userId);
        } else {
            $user = $this->createB2BUser($request);
        }

        if ($user) {

            return response()->json([
                'success' => true,
            ]);
        }


        return response()->json([
            'success' => false,
        ]);
    }


    private function createB2BUser(Request $request)
    {

        $b2bPartnerId = null;

        if (
            $request->has('partner') &&
            ($partner = B2BPartner::where('code', $request->get('partner'))->first())
        ) {
            $b2bPartnerId = $partner->id;
        }

        $user = User::where('email', $request->email)->first();
        $user = $user instanceof User ? $user : new User();

        $password = Str::random(8);

        $user->nickname = $request->companyName;
        $user->email = $request->email;
        $user->firstname = $request->firstName;
        $user->lastname = $request->lastName;
        $user->password = Hash::make($password);
        $user->b2b_partner_id = $b2bPartnerId;

        if ($user->save()) {

            $b2bUser = B2BUser::where('name', $request->companyName)->first();
            $b2bUser = $b2bUser instanceof  B2BUser ? $b2bUser : new B2BUser();

            $b2bUser->name = $request->companyName;
            $b2bUser->vat_id = $request->companyVatID;
            
            $role= $this->getB2BTypeByProductId($request->productVariantId);
            $user->assignRole($role);

            if($user->b2bUser()->save($b2bUser)) {

                event(new RegisteredCompany($user));

                $sendinBlue = new SendinBlueHandler($user);
                $sendinBlue->createContact();

                try {
                    $customer = $this->billwerkApi->getCustomer($request->customerId);
                    $customer->Tag = $user->id;
                    $this->billwerkApi->updateExternalCustomerId($customer);
                } catch (ClientException $e) {
                    Log::error('Something went wrong in B2BController::createB2BUser: ' . $e->getMessage());
                }
            }


            return $user;
        }

        return false;
    }


    private function getB2BTypeByProductId(String $productVariantId) {
        $funeralPlans = [
            config('billwerk.plans.b2b.50x_1_months'),
            config('billwerk.plans.b2b.100x_1_months'),
            config('billwerk.plans.b2b.200x_1_months'),
            config('billwerk.plans.b2b.flatrate_1_years'),
        ];

        return (in_array($productVariantId, $funeralPlans)) ? 'funeral-company' : 'company';
    }
    public function validateUser(Request $request): \Illuminate\Http\JsonResponse|array
    {
        Validator::make($request->all(), [
            'email' => 'unique:users',
            'name' => 'unique:b2b_users'
        ])->validate();

        return response()->json([
            'valid' => true,
            'data' => [
                'message' => ''
            ]
        ], 200);
    }


    public function getByUserId(Request $request, $user_id)
    {
        abort_if(!$user_id, 404, 'User ID not found');

        $b2bUser = B2BUser::where('user_id', $user_id)
                ->select(['id'])
                ->with(['b2bPartner' => function($query) {
                    $query->select(['id', 'b2b_user_id']);
                }])
                ->first();

        abort_if(!$b2bUser, 404, 'B2B User not found');

        return response()->json($b2bUser);
    }

}
