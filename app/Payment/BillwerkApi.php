<?php

namespace App\Payment;

use App\Billwerk;
use App\Mail\CouponSoldEmail;
use App\SendinBlue\SendinBlueHandler;
use App\User;
use App\B2BUser;
use App\B2BCoupon;
use App\B2BCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Psr\Http\Message\ResponseInterface;
use DateTime;
use Exception;

class BillwerkApi
{
    private $client;
    private $publicApiKey;
    private $token;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Prepares the billwerk request with an access token
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function prepare()
    {
        $token = Billwerk::where('key', 'token')->first();
        if (!$token) {
            $token = new Billwerk();
            $token->key = 'token';
        }
        $token = $this->validateToken($token);
        $this->setAccessToken($token);
        $this->setPublicApiKey(config('billwerk.auth.public_api_key'));
    }

    /**
     * Sets the current access token
     *
     * @param $token
     */

    private function setAccessToken($token)
    {
        $this->token = $token;
    }



    /**
     * Gets the current access token
     *
     * @return mixed
     */

    private function getAccessToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $publicApiKey
     */
    public function setPublicApiKey($publicApiKey): void
    {
        $this->publicApiKey = $publicApiKey;
    }

    /**
     * @return mixed
     */
    public function getPublicApiKey()
    {
        return $this->publicApiKey;
    }

    /**
     * Checks if the billwerk access token has to be updated
     *
     * @param Billwerk $token
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function validateToken(Billwerk $token)
    {
        if (!$token->value || $this->needsRefresh($token->updated_at)) {
            return $this->refreshToken($token);
        }
        return $token->value;
    }

    /**
     * The token should live forever, but let us refresh them every 60 minutes.
     *
     * @param $updatedAt
     * @return bool
     * @throws \Exception
     */

    private function needsRefresh($updatedAt)
    {
        $now = Carbon::now();
        $end = new Carbon($updatedAt);
        return $end->diffInMinutes($now) > 60;
    }

    /**
     * A wrapper functin for storing the access token in the db too
     * for further requests
     *
     * @param Billwerk $token
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function refreshToken(Billwerk $token)
    {
        $newAccessToken = $this->authenticate();
        $token->value = $newAccessToken;
        $token->save();
        return $newAccessToken;
    }

    /**
     * Fetches an access token based on client_id and client_secret
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function authenticate()
    {
        try {
            $response = $this->client->request('POST', config('billwerk.routes.auth'), [
                'auth' => [
                    config('billwerk.auth.client_id'),
                    config('billwerk.auth.client_token')
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'form_params' => [
                        'client_id' => config('billwerk.auth.client_id')
                    ],
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $body = $this->getBody($response);
            if ($body) {
                return $body->access_token;
            } else {
                throw new \Exception('no access token provided by billwerk');
            }
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
        }
    }

    /**
     * A helper function to retrieve the body from the billwerk response.
     *
     * @param ResponseInterface $response
     * @return bool|mixed
     */

    private function getBody(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 201) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
     * Uses the incomming Contract comming from the Payment Succeeded Event
     *
     * 1. use the customer id from the contract to query the cusfetchCustomertomer
     * 2. use the "Tag" property to detect our user id. Then set the user to premium.
     * 3. update the externalCustomerId field on billwerk
     *
     * @param string $contractId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function updateUserToPremium($contract, $customer)
    {
        try {

            if (!$contract) {
                throw new BillwerkNoContractFoundError(__METHOD__);
            }

            if (!$customer) {
                throw new BillwerkNoCustomerFoundError(__METHOD__);
            }

            $this->updateUser($customer->Tag, true);
            $this->updateSendinBluePremiumStatus($customer->Tag, $contract->StartDate, true);
            $this->updateExternalCustomerId($customer);
            
        } catch (BillwerkNoContractFoundError | BillwerkNoCustomerFoundError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
            return $e->getMessage();
        }
    }
    
    /**
     * Uses the incomming Contract Id to get full Contact
     *
     * 1. fetch the contract on billwerks side
     *
     * @param string $contractId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

     public function getContact($contractId='')
     {
         if (!$contractId) {
             throw new BillwerkNoContractIdError(__METHOD__);
         }
 
         $this->prepare();
         $contract = $this->fetchContract($contractId);
 
         if (!$contract) {
             throw new BillwerkNoContractFoundError(__METHOD__);
             return false;
         }
        return $contract;
     }
     
     
     /**
     * Uses the incomming Customer Id to get full Customer
     *
     *
     * @param string $customerId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

     public function getCustomer($customerId='')
     {
         if (!$customerId) {
             throw new BillwerkNoCustomerIdError(__METHOD__);
         }
 
         $this->prepare();         
         $customer = $this->fetchCustomer($customerId);

        if (!$customer) {
            throw new BillwerkNoCustomerFoundError(__METHOD__);
            return;
        }
        return $customer;
     }
     
     
 

    /**
     * Check the order on payment manually on succeeded page
     * as well as in order succeded webhook
     *
     * @param string $contractId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkOrder($contractId='', $isCommingFromWebhhok=false)
    {
        try {
            if (!$contractId) {
                throw new BillwerkNoContractIdError(__METHOD__);
            }

            $this->prepare();
            $contract = $this->fetchContract($contractId);

            if (!$contract) {
                throw new BillwerkNoContractFoundError(__METHOD__);
            }

            $customer = $this->fetchCustomer($contract->CustomerId);

            if (!$customer) {
                throw new BillwerkNoCustomerFoundError(__METHOD__);
            }

            $userId = $customer->ExternalCustomerId ?? $customer->Tag ?? null;

            if (!$userId) {
                throw new BillwerkNoExternalCustomerIdError(__METHOD__);
            /**
             * There must be an error when assigning our user id "Tag"
             * in the initial PaymentSucceeded hook
             */
            } elseif (substr($userId, 0, 6) === 'coupon') {

                /**
                 * Just send the Mail once when check Order
                 * is call from the PaymentController which means
                 * where Billwerk Webhook comes in.
                 *
                 * TODO: Refactor sending payment mails
                 * Because billwerk cannot handle different emails when
                 * order succeedes, we have to send them once on the payment
                 * succeeded page. Check payment controller sendMail method
                 */

                if ($isCommingFromWebhhok) {
                    $planVariant = $this->fetchPlanVariant($contract->PlanVariantId);

                    Mail::to($customer->EmailAddress)
                        ->send(new CouponSoldEmail($planVariant, $customer));
                }

                return [
                    'userId' => 0, // HOTFIX: Must be set because of contractChanged Webhook
                    'success' => true,
                    'orderType' => 'coupon',
                    'status' => 'coupon code sold'
                ];
            }

            $isPremium = true;
            $lifecycleStatus = $contract->LifecycleStatus ?? null;

            if (!$lifecycleStatus) {
                throw new BillwerkNoExternalCustomerIdError(__METHOD__);
            }

            if ($lifecycleStatus !== 'Active') {
                $isPremium = false;
                $this->updateUser($userId, $isPremium);
                $this->updateSendinBluePremiumStatus($userId, $contract->EndDate, $isPremium);
            }

            return [
                'userId' => $userId,
                'success' => true,
                'orderType' => 'premium',
                'status' => $isPremium ? 'is still premium' : 'is not premium anymore'
            ];
        } catch (BillwerkNoContractIdError $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        } catch (BillwerkNoContractFoundError $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        } catch (BillwerkNoCustomerFoundError $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        } catch (BillwerkNoExternalCustomerIdError $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        } catch (\Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }
    
    
    /**
     * Checks the sequence of a contract to block automatic extension 
     * 
     *@param string $contractId
     *@return void
     */
    public function checkContractExtension($contractId = null)
    {

        abort_if(!$contractId, 404, 'ContractId not found');

        $this->prepare();
        
        $contract = $this->fetchContract($contractId);

        if (
            !$contract ||
            $contract->PlanGroupId !== config('billwerk.plangroups.standard') ||
            property_exists($contract, "EndDate") ||
            !property_exists($contract, "Phases") ||
            !is_array($contract->Phases)
        ) return false;

        $subscriptions = $this->fetchContractSubscriptions($contractId);

        abort_if(!$subscriptions, 404, 'Contract subscriptions not found');

        if (!is_array($subscriptions->DiscountSubscriptions)) return false;

        $couponCode = array_column($subscriptions->DiscountSubscriptions, 'CouponCode');

        if (empty($couponCode))  return false;

        $exists = B2BCode::whereIn('code', $couponCode)->exists();

        if (!$exists)  return false;

        $extensionPhase = $contract->Phases[1] ?? null;

        if (!$extensionPhase || !property_exists($extensionPhase, "StartDate"))  return false;

        $this->setConctratEndDate($contractId, $extensionPhase->StartDate);
    }


    /**
     * Updates the instances when a contract is cancelled
     *
     * @param string $contractId
     * @param string $customerId
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function contractCancelled($contractId, $customerId)
    {
        try {
            if (!$contractId) {
                throw new BillwerkNoContractIdError(__METHOD__);
            }

            $this->prepare();
            $contract = $this->fetchContract($contractId);

            if (!$contract) {
                throw new BillwerkNoContractFoundError(__METHOD__);
            }

            $customer = $this->fetchCustomer($contract->CustomerId);

            if (!$customer) {
                throw new BillwerkNoCustomerFoundError(__METHOD__);
            }

            $userId = $customer->ExternalCustomerId ?? $customer->Tag ?? null;

            if (!$userId) {
                throw new BillwerkNoExternalCustomerIdError(__METHOD__);
            }

            if(isset($contract->EndDate)) {
                $this->updateUserTerminatedContract($userId, $contract->EndDate);
                $this->updateSendinBlueCancellationStatus($userId);
            }

        } catch (BillwerkNoContractIdError | BillwerkNoContractFoundError | BillwerkNoExternalCustomerIdError | BillwerkNoCustomerFoundError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }

    /**
     * Fetches the Billerk Contract by Contract Id
     *
     * @param $contractId
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function fetchContract($contractId)
    {
        $contractUrl = config('billwerk.routes.contract') . "{$contractId}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $contractUrl)
        );
    }
    
    /**
     * Fetches the Billerk Contract Subscriptions by Contract Id
     *
     * @param $contractId
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

     public function fetchContractSubscriptions($contractId)
     {
         $contractUrl = config('billwerk.routes.contract') . "{$contractId}/subscriptions?access_token={$this->getAccessToken()}";
         return $this->getBody(
             $this->client->request('GET', $contractUrl)
         );
     }


    /**
     * Fetches the Billerk Contract by Contract Id
     *
     * @param $contractId
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function fetchContractCancellationPreview($contractId)
    {
        $contractUrl = config('billwerk.routes.contract') . "{$contractId}/cancellationPreview/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $contractUrl)
        );
    }


    /**
     * Set an end date for this contract
     *
     * @param $contractId
     * @param $contractEndDate
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function setConctratEndDate($contractId, $contractEndDate)
    {
        $customerUrl = config('billwerk.routes.contract') . "{$contractId}/end?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('POST', $customerUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'EndDate' => $contractEndDate
                ]
            ])
        );
    }


    /**
     * Set an end date for this contract
     *
     * @param $contractId
     * @param $contractEndDate
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function storeOrder($order = [])
    {
        $ordersUrl = config('billwerk.routes.orders') . "?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('POST', $ordersUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $order
            ])
        );
    }
    
    
    /**
     * Save coupons for this planGroups
     *
     * @param $planGroupId
     * @param $coupon
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

     public function storeCoupon($planGroupId, $coupon = [])
     {
         $url = config('billwerk.routes.planGroups') . "/{$planGroupId}/coupons";
         return $this->getBody(
             $this->client->request('POST', $url, [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => "Bearer {$this->getAccessToken()}"
                 ],
                 'json' => $coupon
             ])
         );
     }


    public function updateCoupon($couponId, $coupon = [])
    {
        $url = config('billwerk.routes.coupons') . "/{$couponId}";
        return $this->getBody(
            $this->client->request('PUT', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->getAccessToken()}"
                ],
                'json' => $coupon
            ])
        );
    }

    public function fetchCoupon($couponId)
    {
        $url = config('billwerk.routes.coupons') . "/{$couponId}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $url)
        );
    }

    public function fetchDiscount($discountId)
    {
        $url = config('billwerk.routes.discounts') . "/{$discountId}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $url)
        );
    }




    /**
     * Fetches the Billwerk Customer by Customer Id
     *
     * @param $customerId
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function fetchCustomer($customerId)
    {
        $customerUrl = config('billwerk.routes.customer') . "{$customerId}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $customerUrl)
        );
    }
    
    public function fetchCustomers($params = [])
    {
        $search_string = http_build_query($params);
        $customerUrl = config('billwerk.routes.customer') . "?access_token={$this->getAccessToken()}&{$search_string}";
        return $this->getBody(
            $this->client->request('GET', $customerUrl)
        );
    }

    /**
     * Retreive more information about
     * the sold product. You will get details
     * about the billerwek product name and billing period
     *
     * @param $planVariantId
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function fetchPlanVariant($planVariantId)
    {
        $planVariantUrl = config('billwerk.routes.planVariant') . "{$planVariantId}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $planVariantUrl)
        );
    }

    public function getPlanVariant($planVariantId)
    {
        try {

            $this->prepare();
            $planVariant = $this->fetchPlanVariant($planVariantId);
            if (!$planVariant) {
                throw new BillwerkNoPlanVariantFoundError(__METHOD__);
            }
            return $planVariant;
        } catch (BillwerkNoPlanVariantFoundError $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        } catch (\Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }

    /**
     * Updates the external Customer Id so there is a fixed relation on billwerks side.
     * The Tag property is just used for temporary purposes, but will still exists.
     *
     * @param $customer
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function updateExternalCustomerId($customer)
    {
        $customerUrl = config('billwerk.routes.customer') . "{$customer->Id}/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('PATCH', $customerUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'ExternalCustomerId' => $customer->Tag
                ]
            ])
        );
    }

    private function updateUser($userId, $toPremium = false)
    {
        $user = User::find($userId);
        if ($user) {
            $user->is_premium = $toPremium;
            if($toPremium === true) {
                /* the user's contract is being initiated */
                $user->cancellation_at = null;
                $user->premium_end_at  = null;
            } else {
                $user->matching_status = true;
            }
            $user->save();
        }
    }

    private function updateUserTerminatedContract($userId, $date)
    {
        /* the user's contract is being terminated */
        $user = User::find($userId);
        if ($user) {
            if(empty($user->cancellation_at)) $user->cancellation_at = new DateTime();
            if(empty($user->premium_end_at)) $user->premium_end_at  = Carbon::parse($date)->timeZone(config('app.timezone'));
            $user->save();
        }
    }


    private function updateSendinBluePremiumStatus($userId, $date = null, $isPremium = false) {
        $user = User::find($userId);
        if ($user) {
            $sendinBlue = new SendinBlueHandler($user);
            ($isPremium === true)? $sendinBlue->premiumStatusStart($date) : $sendinBlue->premiumStatusEnd($date);
        }
    }

    private function updateSendinBlueCancellationStatus($userId) {
        $user = User::find($userId);
        if ($user) {
            $sendinBlue = new SendinBlueHandler($user);
            $cancellationDate = (empty($user->cancellation_at))? new DateTime() : $user->cancellation_at;
            $sendinBlue->cancellationStatus($cancellationDate);
        }
    }    

    
    public function getCustomers($params = [])
    {
        try {

            $this->prepare();
            $customers = $this->fetchCustomers($params);
            if (!$customers) {
                throw new BillwerkNoCustomerFoundError(__METHOD__);
                return false;
            }

            return $customers;
        } catch (BillwerkNoCustomerFoundError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }

    public function getCustomerByExternalId($externalId = "")
    {
        $customers = $this->getCustomers(["externalId" =>  $externalId]);
        
        if(!is_array($customers)) return null;
        
        return array_values(array_filter($customers, function ($customer) use ($externalId) {
                    $userId = $customer->ExternalCustomerId ?? $customer->Tag ?? null;
                    return intval($userId) === intval($externalId);
                }) ?? [])[0] ?? null;
    }
    
    public function getCustomerByEmailAddress($emailAddress = "")
    {
        $customers = $this->getCustomers(["EmailAddress" =>  $emailAddress]);
        
        if(!is_array($customers)) return null;
        
        return array_values(array_filter($customers, function ($customer) use ($emailAddress) {
                    return property_exists($customer, 'EmailAddress') && $customer->EmailAddress === $emailAddress;
                }) ?? [])[0] ?? null;
    }
    
    public function getCustomerByAddress($address = "")
    {
    $customers = $this->getCustomers();
    if(!is_array($customers)) return null;
        
        return array_values(array_filter($customers, function ($customer) use ($address) {
                    return property_exists($customer, 'Address') && 
                            property_exists($customer->Address, 'AddressLine1') && $customer->Address->AddressLine1 === $address;
                }) ?? [])[0] ?? null;
    }

    public function fetchCustomerContracts($customerId)
    {
        $customerUrl = config('billwerk.routes.customer') . "{$customerId}/contracts/?access_token={$this->getAccessToken()}";
        return $this->getBody(
            $this->client->request('GET', $customerUrl)
        );
    }

    public function getCountries()
    {
        $url = config('billwerk.routes.countries') . "?entityId={$this->getPublicApiKey()}";
        return $this->getBody(
            $this->client->request('GET', $url)
        );
    }

    public function getActiveContract($customerId)
    {
        try {

            if (!$customerId) {
                throw new BillwerkNoCustomerIdError(__METHOD__);
            }

            $this->prepare();
            $contracts = $this->fetchCustomerContracts($customerId);
            $contract = array_values(array_filter($contracts, function ($contract) {
                        return $contract->LifecycleStatus === 'Active';
                    }) ?? [])[0] ?? null;
            if (!$contract) {
                throw new BillwerkNoContractFoundError(__METHOD__);
            }

            return $contract;
        } catch (BillwerkNoCustomerIdError | BillwerkNoContractFoundError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }

    public function getContractCancellationDate($contactId)
    {
        try {

            if (!$contactId) {
                throw new BillwerkNoContractIdError(__METHOD__);
            }

            $this->prepare();
            $contract = $this->fetchContractCancellationPreview($contactId);
            if (!$contract) {
                throw new BillwerkNoContractFoundError(__METHOD__);
            }
            return $contract->EndDate;
        } catch (BillwerkNoContractIdError | BillwerkNoContractFoundError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }

    public function contractCancellation($contactId, $contractEndDate)
    {
        try {

            if (!$contactId) {
                throw new BillwerkNoContractIdError(__METHOD__);
            }

            if (!$contractEndDate) {
                throw new BillwerkNoContractEndDateError(__METHOD__);
            }

            $this->prepare();
            return $this->setConctratEndDate($contactId, $contractEndDate);
        } catch (Guzzle\Http\Exception\ClientErrorResponseException $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
            return $e->getResponse()->getBody(true);
        } catch (BillwerkNoContractIdError | BillwerkNoContractEndDateError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
            return $e->getMessage();
        }
    }

    public function createOrder($customerId, $customer, $cart, $partner)
    {
        try {

            if (!$customerId) {
                throw new BillwerkNoCustomerIdError(__METHOD__);
            }
            $request = [
                "TriggerInterimBilling" => false,
                "CustomerId" => $customerId,
                "CustomerChange" => $customer,
                "Cart" => $cart,
                "PreviewAfterTrial" => false,
                "ContractCustomFields" => [
                    "Partner" => $partner
                ]
            ];
            $this->prepare();
            $order = $this->storeOrder($request);
            return [
                "ContractId" => $order->Contract->Id,
                "Currency" => $order->Currency,
                "CustomerId" => $order->CustomerId,
                "GrossTotal" => $order->TotalGross,
                "OrderId" => $order->Id,
                "OrderStatus" => $order->Status,
            ];
        } catch (BillwerkNoCustomerIdError | \Exception $e) {
            Log::debug('PAYMENT ERROR: '.__METHOD__.' '. $e->getMessage());
        }
    }


    public function generateCoupon(string $planGroupId, string $name, string $discountId, string $validUntil = null, array $codes = [])
    {
        $this->prepare();

        $data = [
            "PlanGroupId" => $planGroupId,
            "Active" => true,
            "InternalName" => $name,
            "Description" => ["_c"=> ""],
            "DiscountId" => $discountId,
            "Enabled" => true,
            "Codes" => $codes,
            "IsSingleUse" => true,
            "Hidden" => false,
            "ValidUntil" => $validUntil,
            "UsedCodes" => []
        ];

        $coupon = $this->storeCoupon($planGroupId, $data);

        abort_if(!$coupon, 404, 'Billwerk Coupon not created');

        return $coupon;

    }

    public function generateCodes(string $couponId, array $codes = [])
    {
        abort_if(!$couponId, 404, 'Coupon ID not found');

        $this->prepare();
        $coupon = $this->getCoupon($couponId);

        abort_if(!$coupon, 404, 'Coupon not found in Billwerk');

        $coupon->Codes = array_merge($coupon->Codes, array_column($codes, 'code'));

        $save = $this->updateCoupon($couponId, $coupon);

        abort_if(!$save, 404, 'Coupon not updated in Billwerk');

        return $save;
    }

    public function getDiscount($discountId)
    {
        abort_if(!$discountId, 404, 'Discount ID not found');

        $this->prepare();
        $discount = $this->fetchDiscount($discountId);

        abort_if(!$discount, 404, 'Discount not found in Billwerk');

        return $discount;
    }
    public function getCoupon($couponId)
    {
        abort_if(!$couponId, 404, 'Coupon ID not found');

        $this->prepare();
        $coupon = $this->fetchCoupon($couponId);

        abort_if(!$coupon, 404, 'Coupon not found in Billwerk');

        return $coupon;
    }

    public function getCountriesList()
    {
        $this->prepare();
        $countries = $this->getCountries();
        abort_if(!$countries, 404, 'Countries not found in Billwerk');

        return $countries;
    }


}
