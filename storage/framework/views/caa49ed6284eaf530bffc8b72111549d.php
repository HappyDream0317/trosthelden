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
            Scrolle zu den Laufzeiten-Buttons, wähle > <?php echo e($product); ?> <?php echo e($product > 1 ? "Monate" : "Monat"); ?>

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
            <?php if(auth()->user()->hasPermissionTo('view funeral-company_intro')): ?>
                Wähle das Zahlungsmittel* aus
            <?php endif; ?>
            <?php if(auth()->user()->hasPermissionTo('view company_intro')): ?>
                Wähle das Zahlungsmittel aus (das ist aus technischen Gründen notwendig)
            <?php endif; ?>
        </li>
        <li>
            Setze ein Häkchen bei > „Ich akzeptiere die AGBs von TrostHelden.“
        </li>
        <li>
            Klicke auf den Button > „Jetzt zahlungspflichtig bestellen“
        </li>
    </ul>
    <?php if(auth()->user()->hasPermissionTo('view funeral-company_intro')): ?>
        <p>
            *Hinweis: Mit einem gültigen Gutschein-Code bucht TrostHelden selbstverständlich für den
            betreffenden Monat kein Geld ab.
        </p>
        <p>
            Nach Ablauf des Monats verlängert sich deine Mitgliedschaft monatlich automatisch zum
            Preis von 19,90 €. Du kannst deine Mitgliedschaft bis 7 Tage vor Ablauf des Abos kündigen.
        </p>
    <?php endif; ?>
    <?php if(auth()->user()->hasPermissionTo('view company_intro')): ?>
        <p>
            Hinweis: Mit einem gültigen Code bucht TrostHelden selbstverständlich kein Geld ab.
        </p>
        <p>
            Nach Ablauf der sechs Monate geht deine Mitgliedschaft automatisch in einen kostenlose
            Basis-Mitgliedschaft über.
        </p>
    <?php endif; ?>
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
</div><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/documents/components/instructions.blade.php ENDPATH**/ ?>