<?php

namespace App\SendinBlue;

use App\User;
use App\SendinBlue\SendinBlueApi;
use App\SendinBlue\SendinBlueTracker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendinBlueHandler
{
    protected User $user;
    protected SendinBlueApi $sendinBlueApi;
    protected SendinBlueTracker $sendinBlueTracker;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->sendinBlueApi = new SendinBlueApi();
        $this->sendinBlueTracker = new SendinBlueTracker();
    }

    public function updateSendinblueContact(array $attributes) {
        $this->sendinBlueApi->updateContact($this->user, $attributes);
        Log::debug('SENDINBLUE UPDATE CONTACTS ATTRIBUTE: user: '.$this->user->email.' attributes: '.json_encode($attributes));
    }

    public function emitSendinblueEvent(string $event, array $attributes) {
        $this->sendinBlueTracker->event(
            $event,
            $this->user,
            $attributes
        );
        Log::debug('SENDINBLUE EMIT EVENT : user: '.$this->user->email.' event: '.$event.' attributes: '.json_encode($attributes));
    }

    public function createContact()
    {
        $exist = $this->getContact();
        if(!$exist) {
            return $this->sendinBlueApi->createContact($this->user);
        }
        
        $attributes = [
            'REGISTER_DATE' => Carbon::now()->format("Y-m-d"),
            'DELETION_DATE' => '',
            'PREMIUM' => false,
            'PREMIUM_START_DATE' => '',
            'CANCELLATION_DATE' =>  '',
            'PREMIUM_END_DATE' => '',
            'BILLWERK_PRODUCT_VARIANT ' => '',
        ];
        
        return $this->updateSendinblueContact($attributes);
    }
    
    public function getContact()
    {
        return $this->sendinBlueApi->getContact($this->user);
    }

    public function premiumStatusStart($startDate = null)
    {
        $date = $startDate === null ? Carbon::now() : Carbon::parse($startDate)->timeZone(config('app.timezone'));

        $attributes = [
            'PREMIUM' => true,
            'PREMIUM_START_DATE' => $date->format("Y-m-d")
        ];
        $this->updateSendinblueContact($attributes);
    }

    public function premiumStatusEnd($endDate = null)
    {
        $date = $endDate === null ? Carbon::now() : Carbon::parse($endDate)->timeZone(config('app.timezone'));

        $attributes = [
            'PREMIUM' => false,
            'PREMIUM_END_DATE' => $date->format("Y-m-d"),
        ];
        $this->updateSendinblueContact($attributes);
    }

    public function notificationStatus(array $data){
        $attributes = [
            'NOTIFICATION_AKTIONEN' => $data['promotions'],
            'NOTIFICATION_BEFRAGUNG' => $data['surveys'],
            'NOTIFICATION_AUTOMATION' => $data['automation'],
        ];

        $this->updateSendinblueContact($attributes);
    }

    public function cancellationStatus($cancellationDate = null){

        $date = $cancellationDate === null ? Carbon::now() : Carbon::parse($cancellationDate)->timeZone(config('app.timezone'));

        $attributes = [
            'CANCELLATION_DATE' => $date->format("Y-m-d")
        ];

        $this->updateSendinblueContact($attributes);
    }


    public function emitUserPremium(array $data)
    {
        $attributes = [
            'PREMIUM' => true,
            'BILLWERK_EMAIL' => $data['email'],
            'BILLWERK_PRODUCT_VARIANT' => $data['productVariant'],
            'BILLWERK_PAYMENT_METHOD' => $data['paymentMethod'],
            'BILLWERK_VOUCHER' => $data['couponCode'],
        ];

        $this->premiumStatusStart();
        $this->emitSendinblueEvent(config('sendinblue.events.user_premium'),$attributes);
    }

    public  function emitPremiumPaywallSeen(){
        $attributes = [
            'PREMIUM_PAYWALL_SEEN' => true,
        ];
        $this->emitSendinblueEvent(config('sendinblue.events.send_request'),$attributes);
    }

    public function emitUserDeleted()
    {
        $attributes = [
            'AUTOMATION_KEY' => 99,
            'DELETION_DATE' => Carbon::now()->format("Y-m-d")
        ];
        $this->emitSendinblueEvent(config('sendinblue.events.user_deleted'),$attributes);
    }

    public  function emitNewRegistration(){

        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('DOUBLE_OPT-IN'));

        if(!$hasSeenElement) {
            $attributes = [
                'DOUBLE_OPT-IN' => 1,
                'NOTIFICATION_AKTIONEN' => true,
                'NOTIFICATION_BEFRAGUNG' => true,
                'NOTIFICATION_AUTOMATION' => true,
                'REGISTER_DATE' => Carbon::now()->format("Y-m-d")
            ];
            $this->emitSendinblueEvent(config('sendinblue.events.new_registration'),$attributes);
            $this->user->addHasSeen($this->user, 'DOUBLE_OPT-IN');
        }
    }

    public function emitBereavedBereavementCaregiver($type  = 'mourner') {
        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('MEMBER_TYPE'));

        if(!$hasSeenElement) {
            $attributes = ['MEMBER_TYPE' => $type];
            $this->emitSendinblueEvent(config('sendinblue.events.bereaved_bereavement_caregiver'),$attributes);
            $this->user->addHasSeen($this->user, 'MEMBER_TYPE');
        }
    }

    public function emitQuestionnaireFilledOut() {
        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('FRABO_COMPLETE'));

        if(!$hasSeenElement) {
            $attributes = ['FRABO_COMPLETE' => true];
            $this->emitSendinblueEvent(config('sendinblue.events.questionnaire_filled_out'),$attributes);
            $this->user->addHasSeen($this->user, 'FRABO_COMPLETE');
        }
    }

    public function emitForeignProfileViewed() {
        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('FOREIGN_PROFILE_VIEWED'));

        if(!$hasSeenElement) {
            $attributes = ['FOREIGN_PROFILE_VIEWED' => true];
            $this->emitSendinblueEvent(config('sendinblue.events.foreign_profile_viewed'),$attributes);
            $this->user->addHasSeen($this->user, 'FOREIGN_PROFILE_VIEWED');
        }
    }

    public function emitProfileSloganFilledOut() {
        $attributes = ['PROFILE_SLOGAN_SET' => true];
        $this->emitSendinblueEvent(config('sendinblue.events.profile_slogan_filled_out'),$attributes);
    }

    public function emitProfilePictureUploaded() {
        $attributes = ['PROFILE_PICTURE_SET' => true];
        $this->emitSendinblueEvent(config('sendinblue.events.profile_picture_uploaded'),$attributes);
    }

    public function emitMessagesSentAtLeast2People() {
        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('MESSAGE_2_MOURNERS_CONTACTED'));

        if(!$hasSeenElement) {
            $messagesReceivedCount = (int)$this->user->getChatsWithMessagesSendedCount();

            if($messagesReceivedCount && $messagesReceivedCount >= 2){

                $attributes = ['MESSAGE_2_MOURNERS_CONTACTED' => true];
                $this->emitSendinblueEvent(config('sendinblue.events.messages_sent_at_least_2_people'),$attributes);

                $this->user->addHasSeen($this->user, 'MESSAGE_2_MOURNERS_CONTACTED');
            }
        }
    }

    public function emitAtLeast1MessageReceived() {
        $hasSeenElement = $this->user->hasSeenElementByName(strtolower('MESSAGES_1_RECEIVED'));

        if(!$hasSeenElement) {
            $messagesReceivedCount = $this->user->getChatMessagesReceivedCount();

            if($messagesReceivedCount && $messagesReceivedCount >= 1){
                $attributes =  ['MESSAGES_1_RECEIVED' => true];
                $this->emitSendinblueEvent(config('sendinblue.events.at_least_1_message_received'),$attributes);

                $this->user->addHasSeen($this->user, 'MESSAGES_1_RECEIVED');
            }
        }
    }

}