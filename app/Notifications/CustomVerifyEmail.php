<?php

namespace App\Notifications;

use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->greeting(Lang::get('Hallo TrostHeld!'))
            ->subject(Lang::get('Willkommen bei TrostHelden'))
            ->line(Lang::get('wir freuen uns sehr, dass du dich bei TrostHelden angemeldet hast. Um deine Anmeldung zu vervollständigen, benötigen wir nur noch deine Bestätigung durch einen Klick!'))
            ->action(Lang::get('E-Mail-Adresse bestätigen'), $verificationUrl)
            ->line(Lang::get('Nach deiner Bestätigung wird dein TrostHelden-Zugang aktiv und es steht nichts mehr im Wege, deinen Trauerfreund zu finden!'))
            ->line(Lang::get('Wir wünschen dir viele gute Erfahrungen auf diesem neuen Weg deiner Trauerarbeit,'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
