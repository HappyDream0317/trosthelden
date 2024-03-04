@php
    /**
    * @var $user App\User
    * @var $friend App\User
    * @var $friendRequestUrl string
    */
@endphp

@component('mail::message')
# Ein Trauerfreund für dich persönlich

Hallo {{ $user->nickname }},
es gibt eine Trauerfreund-Anfrage für dich! Dieser besondere Mensch könnte perfekt zu dir passen.

Schau dir doch einmal das Profil dieses TrostHelden-Mitglieds an. Vielleicht hast du bereits eine Nachricht zusammen mit der Anfrage erhalten.

Dieser Trauerfreund spricht dich an? Dann akzeptiere einfach seine Anfrage, damit ihr euch näher kennenlernen könnt.
@component('mail::button', ['url' => $friendRequestUrl, 'color' => 'gold'])
Trauerfreund-Anfragen
@endcomponent

Wir wünschen dir von Herzen alles Gute,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
@endcomponent
