@php
    /**
    * @var $user App\User
    * @var $newMessageCount integer
    * @var $chatUrl string
    */
@endphp

@component('mail::message')
# Es gibt Neuigkeiten von deinem Trauerfreund

Hallo {{ $user->nickname }},
schau gleich mal in deine Nachrichten-Übersicht. Denn du hast {{ $newMessageCount }} neue Nachricht(en) in deinem TrostHelden-Postfach!
@component('mail::button', ['url' => $chatUrl, 'color' => 'gold'])
Meine Nachrichten
@endcomponent

Wir wünschen euch einen wertvollen Austausch.<br>
Herzliche Grüße,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
@endcomponent
