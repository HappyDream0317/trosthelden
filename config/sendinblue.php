<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SendinBlue's main API Key
    |--------------------------------------------------------------------------
    */
    'api_key' => env('SENDINBLUE_API_KEY', ''),
    /*
    |--------------------------------------------------------------------------
    | SendinBlue's Tracker API Key
    |--------------------------------------------------------------------------
    */
    'tracker_id' => env('SENDINBLUE_TRACKER_ID', ''),
    /*
    |--------------------------------------------------------------------------
    | SendinBlue's List IDs
    |--------------------------------------------------------------------------
    */
    'list' => [
        'members' => env('SENDINBLUE_LIST_MEMBERS', ''),
        'crm' => env('SENDINBLUE_LIST_CRM', ''),
    ],
    /*
    |--------------------------------------------------------------------------
    | SendinBlue's Routes
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'tracker' => env('SENDINBLUE_ROUTE_TRACKER', ''),
    ],
    /*
    |--------------------------------------------------------------------------
    | SendinBlue's Events Names
    |--------------------------------------------------------------------------
    */
    'events' => [
        'new_registration' =>                   env('SENDINBLUE_EVENT_NEW_REGISTRATION', 'Neue Anmeldung'),
        'bereaved_bereavement_caregiver' =>     env('SENDINBLUE_EVENT_BEREAVED_BEREAVEMENT_CAREGIVER', 'Trauernder oder Trauerhelfer'),
        'questionnaire_filled_out' =>           env('SENDINBLUE_EVENT_QUESTIONNAIRE_FILLED_OUT', 'Fragebogen ausgefüllt'),
        'profile_slogan_filled_out' =>          env('SENDINBLUE_EVENT_PROFILE_SLOGAN_FILLED_OUT', 'Profilspruch ausgefüllt'),
        'profile_picture_uploaded' =>           env('SENDINBLUE_EVENT_PROFILE_PICTURE_UPLOADED', 'Profilbild hochgeladen'),
        'foreign_profile_viewed' =>             env('SENDINBLUE_EVENT_FOREIGN_PROFILE_VIEWED', 'Fremdprofil angeschaut'),
        'send_request' =>                       env('SENDINBLUE_EVENT_SEND_REQUEST', 'Anfrage versenden'),
        'user_premium' =>                       env('SENDINBLUE_EVENT_USER_PREMIUM', 'Nutzer ist Premium'),
        'messages_sent_at_least_2_people' =>    env('SENDINBLUE_EVENT_MESSAGES_SENT_AT_LEAST_2_PEOPLE', 'Nachrichten an (mindestens) 2 Personen verschickt'),
        'at_least_1_message_received' =>        env('SENDINBLUE_EVENT_AT_LEAST_1_MESSAGE_RECEIVED', '(mindestens) 1 Nachricht erhalten'),
        'user_deleted' =>                       env('SENDINBLUE_EVENT_USER_DELETED', 'Nutzer geloescht'),
    ]
];
