<div style="display: block; width: 100%; font-size: 14px; line-height: 18px; color: #0d243d;">
    <p>
        Hallo und guten Tag,
    </p>
    <p>
        schön, dass du zu TrostHelden kommst!
    </p>
    <p>
        Denn TrostHelden vermittelt Trauerfreund:innen mit einem ähnlichen Schicksalsschlag, die
        einander wirklich verstehen.
    </p>
    <p>
        <b>So löst du deinen Gutschein-Code ein:</b>
    </p>
    <ul>
        <li>
            Gehe auf > www.trosthelden.de
        </li>
        <li>
            Klicke im Menü oben rechts auf > Mitgliedschaften
        </li>
        <li>
            Scrolle zu den Laufzeiten-Buttons, wähle > {{ $product }} {{ $product > 1 ? "Monate" : "Monat" }}
        </li>
        <li>
            Registriere dich mit Benutzernamen, E-Mail-Adresse, Passwort
        </li>
        <li>
            Fülle die Bestelldaten aus
        </li>
        <li>
            Setze ein Häkchen bei > „Ich habe einen Coupon-Code“
        </li>
        <li>
            Gib den Code von deinem TrostHelden-Gutschein ein
        </li>
        <li>
            Klicke auf > Code prüfen
        </li>
        <li>
            @if(auth()->user()->hasPermissionTo('view funeral-company_intro'))
                Wähle das Zahlungsmittel* aus
            @endif
            @if(auth()->user()->hasPermissionTo('view company_intro'))
                Wähle das Zahlungsmittel aus (das ist aus technischen Gründen notwendig)
            @endif
        </li>
        <li>
            Setze ein Häkchen bei > „Ich akzeptiere die AGBs von TrostHelden.“
        </li>
        <li>
            Klicke auf den Button > „Jetzt zahlungspflichtig bestellen“
        </li>
    </ul>
    @if(auth()->user()->hasPermissionTo('view funeral-company_intro'))
        <p>
            *Hinweis: Mit einem gültigen Gutschein-Code bucht TrostHelden selbstverständlich für den
            betreffenden Monat kein Geld ab.
        </p>
        <p>
            Nach Ablauf des Monats verlängert sich deine Mitgliedschaft monatlich automatisch zum
            Preis von 19,90 €. Du kannst deine Mitgliedschaft bis 7 Tage vor Ablauf des Abos kündigen.
        </p>
    @endif
    @if(auth()->user()->hasPermissionTo('view company_intro'))
        <p>
            Hinweis: Mit einem gültigen Code bucht TrostHelden selbstverständlich kein Geld ab.
        </p>
        <p>
            Nach Ablauf der sechs Monate geht deine Mitgliedschaft automatisch in einen kostenlose
            Basis-Mitgliedschaft über.
        </p>
    @endif
    <ul>
        <li>
            Nach erfolgreicher Code-Eingabe gelangst du zurück zur Startseite von TrostHelden.
        </li>
        <li>
            Fülle den Fragebogen komplett aus. Dann kann TrostHelden dir Trauerfreund:innen vor-
            schlagen, die perfekt zu dir passen.
        </li>
        <li>
            Jetzt noch dein Profil ergänzen – fertig!
        </li>
        <li>
            TrostHelden schlägt dir deine persönlichen Trauerfreund:innen vor.
        </li>
    </ul>
    <p>
        Das TrostHelden-Team wünscht dir einen heilsamen Austausch!
    </p>
</div>