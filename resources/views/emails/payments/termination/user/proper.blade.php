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
        deine fristgerechte Kündigung vom {{ Carbon\Carbon::now()->format("d.m.Y") }} um {{ Carbon\Carbon::now()->format('H:i') }} Uhr ist bei uns eingegangen.
    </p>
    <p>
        Dein Benutzername: {{ $user->nickname }} ({{ $user->id }})
    </p>
    <p>
        Deine Mitgliedschaft: {{ $product }} {{ $product > 1 ? "Monate" : "Monat" }} TrostHelden-Mitgliedschaft
    </p>
    <p>
        Dein Vertrag wird somit zum {{ $endDate->format("d.m.Y") }} fristgerecht gekündigt. Solltest du noch Fragen haben, sende gern eine E-Mail mit deinem Benutzernamen an <a href="mailto:abo@trosthelden.de">abo@trosthelden.de</a>.
    </p>
    <br>
    <p>
        Vielen Dank, dass du Mitglied bei TrostHelden warst!
    </p>
    <br>
    <p>
        Hat dir TrostHelden geholfen? Dann empfiehl uns gern weiter! Damit noch mehr Trauernde ihre:n Trauerfreund:in finden.
    </p>
    <br>
    <p>
        Herzlichen Dank und liebe Grüße,
    </p>
    <p>
        dein TrostHelden-Team
    </p>
@endcomponent