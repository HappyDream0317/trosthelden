<?php

namespace App\Notifications\B2B;

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

    public function __construct()
    {
    }

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
            ->greeting(Lang::get('Hallo')." {$notifiable->firstname} {$notifiable->lastname}")
            ->subject(Lang::get('Willkommen bei TrostHelden'))
            ->line(Lang::get('hiermit erhalten Sie Ihren persönlichen Zugang zu Ihrem neuen TrostHelden-Account.'))
            ->line(Lang::get('Um Ihre Registrierung abzuschließen, müssen Sie lediglich Ihre E-Mail-Adresse bestätigen und anschließend Ihr Passwort ändern.'))
            ->action(Lang::get('E-Mail-Adresse bestätigen'), $verificationUrl)
            ->line(Lang::get('Nach Ihrer Bestätigung und der Änderung des Passwortes ist Ihr Account aktiv.'))
            ->line(Lang::get('In Ihrem Account haben Sie die Möglichkeit, Ihre erworbenen Gutscheine einzusehen und zu verwalten.'));

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
            'b2b.verification.verify',
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
