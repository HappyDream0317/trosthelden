<?php

namespace App\SendinBlue;

use App\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Brevo\Client\Api\ContactsApi;
use Brevo\Client\Api\AttributesApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use Brevo\Client\Model\UpdateContact;

class SendinBlueApi
{
    private $config;
    private $client;

    public function __construct()
    {
        $this->config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('sendinblue.api_key'));
        $this->client = new Client([
            'verify' => (config('app.env') !== 'local'),
        ]);
    }

    public function createContact(User $user)
    {
        $apiInstance =  new ContactsApi(
            $this->client,
            $this->config
        );

        $createContact = new CreateContact();
        $createContact->setEmail($user->email);
        $createContact->setAttributes(['MEMBER_ID' => $user->id, 'MEMBER_USERNAME' => $user->nickname]);

        $listMembersId = (int)config('sendinblue.list.members');
        $listCRMId = (int)config('sendinblue.list.crm');
        $createContact->setListIds([$listMembersId, $listCRMId]);

        try {
            return $apiInstance->createContact($createContact);
        } catch (\Exception $e) {
            Log::debug('SENDINBLUE CREATE CONTACT: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getContact(User $user)
    {
        $apiInstance =  new ContactsApi(
            $this->client,
            $this->config
        );
        
        try {
            return $apiInstance->getContactInfo($user->email);
        } catch (\Exception $e) {
            Log::debug('SENDINBLUE GET CONTACT: user: '.$user->email.' ' . $e->getMessage());
            return null;
        }
    }

    public function updateContact(User $user, array $attributes)
    {
        $apiInstance =  new ContactsApi(
            $this->client,
            $this->config
        );

        $updateContact = new UpdateContact();
        $updateContact->setAttributes($attributes);

        try {
            $result = $apiInstance->updateContact($user->email, $updateContact);
            return $result;
        } catch (\Exception $e) {
            Log::debug('SENDINBLUE UPDATE CONTACTS ATTRIBUTE: user: '.$user->email.' attributes: '.json_encode($attributes).' '. $e->getMessage());
        }

    }

    public function getAttribute(User $user, $attribute)
    {
        $apiInstance =  new ContactsApi(
            $this->client,
            $this->config
        );

        try {
            $result = $apiInstance->getContactInfo($user->email);
            $resulttAttributes = $result->getAttributes();
            $attributes = collect($resulttAttributes);
            return $attributes->get($attribute);
        } catch (\Exception $e) {
            Log::debug('SENDINBLUE GET ATTRIBUTE: user: '.$user->email.' attribute: '.$attribute.' '. $e->getMessage());
            
        }
    }
    
    public function getAllAttributesDefinition()
    {
        $apiInstance =  new AttributesApi(
            $this->client,
            $this->config
        );

        try {
            return $apiInstance->getAttributes();
        } catch (\Exception $e) {
            Log::debug('SENDINBLUE GET ALL ATTRIBUTES DEFINITION: '. $e->getMessage());
            
        }
    }
}
