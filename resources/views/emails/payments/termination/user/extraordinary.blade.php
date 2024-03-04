@php
    /**
    * @var $user App\User
    */
@endphp

@component('mail::message')
    <p>
        Hallo {{ $user->firstname ?? $customer->FirstName }} {{ $user->lastname ?? $customer->LastName}},
    </p>
    <br>
    <p>
        deine außerordentliche Kündigung vom {{ Carbon\Carbon::now()->format("d.m.Y") }} um {{ Carbon\Carbon::now()->format('H:i') }} ist bei uns eingegangen.
    </p>
    <p>
        Dein Benutzername: {{ $user->nickname }} ({{ $user->id }})
    </p>
    <p>
        Deine Mitgliedschaft: {{ $product }} {{ $product > 1 ? "Monate" : "Monat" }} TrostHelden-Mitgliedschaft
    </p>
    <p>
        Du hast folgenden Grund für die außerordentliche Kündigung angegeben: {{ $reason }}
    </p>
    <br>
    <p>
        Wir melden uns bei dir, sobald wir deine Kündigung geprüft haben. Solltest du Fragen haben, sende gern eine E-Mail mit deinem Benutzernamen an <a href="mailto:abo@trosthelden.de">abo@trosthelden.de</a>.
    </p>
    <br>
    <p>
        Viele Grüße,
    </p>
    <p>
        dein TrostHelden-Team
    </p>
@endcomponent
