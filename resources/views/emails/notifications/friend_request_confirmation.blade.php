@php
    /**
    * @var $user App\User
    * @var $friend App\User
    * @var $chatUrl string
    */
@endphp

@component('mail::message')
# Du hast einen neuen Trauerfreund

Hallo {{ $user->nickname }},
wir haben gute Nachrichten für dich!
{{ $friend->nickname }} hat deine Trauerfreund-Anfrage bestätigt. Schreib deinem neuen Trauerfreund doch gleich mal, denn ihr habt viele Gemeinsamkeiten!
@component('mail::button', ['url' => $chatUrl, 'color' => 'gold'])
Nachrichten
@endcomponent

Herzliche Grüße,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
@endcomponent
