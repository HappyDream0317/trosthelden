<?php

namespace App\Http\Controllers;

use App\Mail\B2B\OrderSuccessfulCouponCompanyEmail;
use App\Mail\B2B\OrderSuccessfulCouponFuneralCompanyEmail;
use App\Mail\B2B\OrderSuccessfulFlatFuneralCompanyEmail;
use App\Mail\PaymentWebhookSandboxMail;
use App\Mail\PremiumSoldEmail;
use App\Mail\TerminationErrorInternalMail;
use App\Mail\TerminationInternalMail;
use App\Mail\TerminationUserMail;
use App\Payment\BillwerkApi;
use App\Payment\BillwerkNoContractIdError;
use App\SendinBlue\SendinBlueHandler;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    private $billwerkApi;

    public function __construct(BillwerkApi $billwerkApi)
    {
        $this->billwerkApi = $billwerkApi;
    }

    public function orderSucceeded(Request $request)
    {
        $event = $request->all();

        Mail::to('ms@yesdevs.com')
        ->send(new PaymentWebhookSandboxMail($event, 'Order Succeeded'));

        try {
            $contractId = $event['ContractId'] ?? '';

            $contract = $this->billwerkApi->getContact($contractId);

            abort_if(!$contract, 404, 'Contract not found');

            $customer = $this->billwerkApi->getCustomer($contract->CustomerId);

            abort_if(!$customer, 404, 'Customer not found');

            if ($contract->PlanGroupId === config('billwerk.plangroups.b2b')) {
                $this->handleCompanyContract($contract, $customer);
            } else {
                $this->billwerkApi->updateUserToPremium($contract, $customer);
            }
            
        } catch (BillwerkNoContractIdError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '.$e->getLine().' '.$e->getMessage());
        }

        return response([
            'success' => true
        ], 200);
    }


    public function contractChanged(Request $request)
    {
        $event = $request->all();
        $contractId = isset($event['ContractId']) ? $event['ContractId'] : null;
        
        abort_if(!$contractId || !is_string($contractId), 404, 'ContractId not found');
        
        $status = $this->billwerkApi->checkOrder($contractId, true);
        
        $this->billwerkApi->checkContractExtension($contractId);

        if ($status) {
            $msg = "userId: {$status['userId']} -> {$status['status']}.";
        } else {
            $msg = "contractId: {$contractId} failed!";
        }

        Mail::to('ms@yesdevs.com')->send(new PaymentWebhookSandboxMail($request->all(), "Contract Changed for {$msg}"));

        return response([
            'success' => true
        ], 200);
    }

    public function checkOrder(Request $request, $contractId)
    {
        $order = $this->billwerkApi->checkOrder($contractId);
        return response([
            'success' => $order['success'],
            'userId' => $order['userId'],
            'orderType' => $order['orderType'],
            'status' => $order['status'],
        ], 200);
    }

    public function sendMail(Request $request)
    {
        if (!Auth::check()) {
            return response([], 401);
        }
        $mailType = $request->get('mailType');
        $email = Auth::user()->email;
        if ($mailType === 'premium') {
            Mail::to($email)
                ->send(new PremiumSoldEmail);
        }
//        elseif ($mailType === 'coupon'){} TODO: send coupon mail from here
        return response([
            'mailType' => $mailType
        ], 200);
    }

    public function customerCreated(Request $request)
    {
        Mail::to('ms@yesdevs.com')
            ->send(new PaymentWebhookSandboxMail($request->all(), 'Customer Created'));

        return response([], 200);
    }

    public function contractCreated(Request $request)
    {
        Mail::to('ms@yesdevs.com')
            ->send(new PaymentWebhookSandboxMail($request->all(), 'Contract Created'));

        return response([], 200);
    }

    public function contractCancelled(Request $request)
    {

        $event = $request->all();
        $contractId = $event['ContractId'];
        $customerId = $event['CustomerId'];
        $this->billwerkApi->contractCancelled($contractId, $customerId);

        Mail::to('ms@yesdevs.com')
            ->send(new PaymentWebhookSandboxMail($event, 'Contract Cancelled'));

        return response([], 200);
    }

    public function planVariantsInfo(Request $request, $variantId)
    {
        $planVariant = $this->billwerkApi->getPlanVariant($variantId);
        return response([
            'planVariant' => $planVariant,
        ], 200);
    }

    public function contractCancellationPreview(Request $request, $userId)
    {
        if (!Auth::check()) {
            return response([], 401);
        }
        $contract = null;
        $customer= null;
        $planVariant = null;

        try {

            $customer = $this->billwerkApi->getCustomerByExternalId($userId);
            $contract = $this->billwerkApi->getActiveContract($customer->Id);
            $planVariant = $this->billwerkApi->getPlanVariant($contract->PlanVariantId);
            $endDate = $this->billwerkApi->getContractCancellationDate($contract->Id);

        } catch (\Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '.$e->getLine().''.$e->getMessage());
            return response(["message" => $e->getMessage()], 500);
        }

        return response([
            'contractId' => $contract->Id,
            'contractEndDate' =>  $endDate,
            'customerId' => $customer->Id ?? null,
            'customerEmail' => $customer->EmailAddress ?? null,
            'planVariantId' => $contract->PlanVariantId,
            'planVariantName' => $planVariant->InternalName,
        ], 200);
    }

    public function contractCancellation(Request $request)
    {
        $userId = $request->get('userId');
        $regular = boolval($request->get('regular'));
        $contractId = $request->get('contractId');
        $customerId = $request->get('customerId');
        $contractEndDate = $request->get('contractEndDate');
        $customerEmail = $request->get('customerEmail');
        $params = $request->all();

        $user = User::find($userId);

        abort_if(!$user, 404, 'User not found');

        $customer = $this->billwerkApi->getCustomer($customerId);

        abort_if(!$customer, 404, 'Customer not found');

        if ($regular) {
            $cancellation = $this->billwerkApi->contractCancellation($contractId, $contractEndDate);
            if (!isset($cancellation->EndDate)) {
                Mail::to("abo@trosthelden.de")
                    ->send(new TerminationErrorInternalMail($user, $params, $cancellation));
                abort(404, 'An error occurred during the cancellation process');
            }
        }

        $emails = collect([$user->email]);
        if (isset($customerEmail) && $customerEmail !== $user->email) $emails->push($customerEmail);

        Mail::to($emails)
            ->bcc("abo@trosthelden.de")
            ->send(new TerminationUserMail($user, $params, $customer));

        Mail::to("abo@trosthelden.de")
            ->send(new TerminationInternalMail($user, $params));

        $user->cancellation_at = new DateTime();
        $user->premium_end_at = Carbon::parse($contractEndDate)->timeZone(config('app.timezone'));
        $user->save();

        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->cancellationStatus($user->cancellation_at);

        return response([
            'success' => true
        ], 200);
    }


    function getCustomerIdByAttr(Request $request)
    {

        $customer = null;
        $tag = null;
        $id = null;

        switch ($request->key) {
            case 'EMAIL':
                $customer = $this->billwerkApi->getCustomerByEmailAddress($request->value);
                break;
            case 'ADDRESS_ADDRESSLINE1':
                $customer = $this->billwerkApi->getCustomerByAddress($request->value);
                break;
            default:
                $customer = $this->billwerkApi->getCustomerByExternalId($request->value);
                break;
        }

        if ($customer) {
            $tag = $customer->ExternalCustomerId ?? $customer->Tag ?? null;
            $id = $customer->Id ?? null;
        }

        return response([
            'customer' => compact('tag', 'id'),
        ], 200);
    }

    function getCustomerData (Request $request, $userId) {

        abort_if(!$userId, 404, 'User ID not found');

        $customer = $this->billwerkApi->getCustomerByExternalId($userId);

        abort_if(!$customer, 404, 'Customer not found');

        return response([
            'customer' => $customer,
        ], 200);
    }

    public function createOrder(Request $request)
    {

        $customerId = $request->get('customerId');
        $customer = $request->get('customer');
        $cart = $request->get('cart');
        $partner = $request->get('partner');
        try {
            $order = $this->billwerkApi->createOrder($customerId,$customer, $cart, $partner);
            return response([
                'order' => $order
            ], 200);

        } catch (\Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '.$e->getLine().''.$e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function handleCompanyContract($contract, $customer){

        $planVariantId = $contract->PlanVariantId ?? $contract->Phases[0]->PlanVariantId ?? null;

        abort_if(!$planVariantId, 404, 'Plan Variant ID not found');

        $userId = $customer->ExternalCustomerId ?? $customer->Tag ?? null;

        abort_if(!$userId, 404, 'User ID not found');

        $user = User::has('b2bUser')->with('b2bUser')->find($userId);

        abort_if(!$user, 404, 'User not found');

        $isFlat = $planVariantId === config('billwerk.plans.b2b.flatrate_1_years');

        $b2bCoupon = $user->b2bUser->generateCoupon($contract);

        abort_if(!$b2bCoupon, 404, 'B2B Coupon not created');

        //Finalize process if it's a flat coupon.
        //Do not generate b2b codes.
        if($isFlat) {
            Mail::to($user->email)->send(new OrderSuccessfulFlatFuneralCompanyEmail($user, $customer));
            return true;
        }

        $countCodes = 0;

        switch ($planVariantId) {
            case config('billwerk.plans.b2b.5x_6_months'):
                $countCodes = 5;
                break;
            case config('billwerk.plans.b2b.10x_6_months'):
                $countCodes = 10;
                break;
            case config('billwerk.plans.b2b.20x_6_months'):
                $countCodes = 20;
                break;
            case config('billwerk.plans.b2b.50x_1_months'):
                $countCodes = 50;
                break;
            case config('billwerk.plans.b2b.100x_1_months'):
                $countCodes = 100;
                break;
            case config('billwerk.plans.b2b.200x_1_months'):
                $countCodes = 200;
                break;
        }

        $b2bCodes = $b2bCoupon->generateCodes($countCodes);

        abort_if(!$b2bCodes, 404, 'B2B Code not created');

        // Finalize process if it's a plan of more than 50, 100 or 200 codes.
        // Do not send email code.
        if(in_array($planVariantId, [
            config('billwerk.plans.b2b.50x_1_months'),
            config('billwerk.plans.b2b.100x_1_months'),
            config('billwerk.plans.b2b.200x_1_months')
        ])) {
            Mail::to($user->email)->send(new OrderSuccessfulCouponFuneralCompanyEmail($user, $customer));
        } else {
            Mail::to($user->email)->send(new OrderSuccessfulCouponCompanyEmail($user, $customer));
        }

        return true;
    }


    function getCountries(Request $request) {

        $countries = $this->billwerkApi->getCountriesList();

        abort_if(!$countries, 404, 'Countries not found in Billwerk');

        $countriesCollection = collect($countries);

        $countriesShortList = $countriesCollection->map(fn($item) => ([
            'code' => $item->TwoLetterCode,
            'name' => $item->DE,
        ]))
        ->sortBy('name')
        ->toArray();

        return response()->json([
            'countries' => $countriesShortList,
        ]);

    }


    public function getStandardPlansInfo(Request $request)
    {

        $plansIds = [
            config('billwerk.plans.standard.1_months'),
            config('billwerk.plans.standard.3_months'),
            config('billwerk.plans.standard.6_months'),
        ];
        $plans = [];

        foreach ($plansIds as $planId) {
            $plan = $this->billwerkApi->getPlanVariant($planId);
            abort_if(!$plan, 404, "Plan Variant {$planId} not found in Billwerk");
            $plans[] = $plan;
        }

        abort_if(empty($plans), 404, "Standard Plans not found in Billwerk");


        $plansCollection = collect($plans);

        $plansShortList = $plansCollection->map(fn($item) => ([
            "period" => $item->ContractPeriod,
            "price" => $item->RecurringFee,
            "code" => $item->Id
        ]))->toArray();

        return response([
            'plans' =>  $plansShortList,
        ]);

    }

    public function isB2BProduct(Request $request, string $variantId)
    {
        abort_if(!$variantId, 404, "Plan Variant {$variantId} not found");

        $result = in_array($variantId, config('billwerk.plans.b2b'));

        return response([
            'result' =>  $result,
        ]);
    }

    public function isFlatrateProduct(Request $request, string $variantId)
    {
        abort_if(!$variantId, 404, "Plan Variant {$variantId} not found");

        $result = $variantId === config('billwerk.plans.b2b.flatrate_1_years');

        return response([
            'result' =>  $result,
        ]);
    }

    public function isStandardProduct(Request $request, string $variantId)
    {
        abort_if(!$variantId, 404, "Plan Variant {$variantId} not found");

        $result = in_array($variantId, config('billwerk.plans.standard')) || in_array($variantId, config('billwerk.codes'));

        return response([
            'result' =>  $result,
        ]);
    }

    public function isCouponProduct(Request $request, string $variantId)
    {
        abort_if(!$variantId, 404, "Plan Variant {$variantId} not found");

        $result = in_array($variantId, config('billwerk.codes'));

        return response([
            'result' =>  $result,
        ]);
    }
}
