<?php

namespace App\Services;

use App\User;

class EventService
{
    const SIGN_UP_EVENT = 'sign_up';
    const LOGIN_EVENT = 'login';

    protected static array $allowedEventTypes = [
        self::SIGN_UP_EVENT,
        self::LOGIN_EVENT
    ];

    /**
     * @param User $user
     * @param string $eventType
     * @return void
     * @throws \Exception
     */
    public static function notify(User $user, string $eventType): void
    {
        if (!in_array($eventType, self::$allowedEventTypes)) {
            throw new \Exception('Not allowed event type: ' . $eventType);
        }

        $post = [
            'event_type' => $eventType,
            'user'   => $user->id,
        ];

        $ch = curl_init('https://fig-orange-2nuoj2otpa-uc.a.run.app/event');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        curl_exec($ch);

        if ($error = curl_error($ch)) {
            throw new \Exception('Failed to notify about event with error: ' . $error);
        }
    }
}
