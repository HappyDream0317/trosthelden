<?php $__env->startComponent('mail::message'); ?>
# Hallo Trostheld!

herzlich Willkommen in der TrostHelden-Community und Glückwunsch zu diesem wertvollen Schritt in deiner Trauerarbeit.<br><br>
Nur noch wenige Schritte trennen dich von deinem Trauerfreund.

<?php $__env->startComponent('mail::contentbox'); ?>
    <h2>Dein Weg zum Trauerfreund</h2>
    <hr>
    <div class="row">
        <div class="icon">
            <img alt="star" src="<?php echo config('app.url') . '/icons/mail/star.png' ?>" />
        </div>
        <div class="text">
            <h3>Fülle den Fragebogen aus</h3>
            <p>Auf Basis deiner Angaben suchen wir nach Trauerfreund-Vorschlägen für dich.</p>
        </div>
    </div>
    <div class="row">
        <div class="icon">
            <img alt="star" src="<?php echo config('app.url') . '/icons/mail/user.png' ?>" />
        </div>
        <div class="text">
            <h3>Wähle aus den Vorschlägen passende Trauerfreunde aus</h3>
            <p>Schau dir dazu die Profile deiner Vorschläge an.</p>
        </div>
    </div>
    <div class="row">
        <div class="icon">
            <img alt="star" src="<?php echo config('app.url') . '/icons/mail/users.png' ?>" />
        </div>
        <div class="text">
            <h3>Versende Anfragen an passende TrostHelden </h3>
            <p>Trau dich auch mehrere Anfragen gleichzeitig zu versenden. </p>
        </div>
    </div>
    <div class="row">
        <div class="icon">
            <img alt="star" src="<?php echo config('app.url') . '/icons/mail/profile.png' ?>" />
        </div>
        <div class="text">
            <h3>Fülle dein persönliches Profil aus</h3>
            <p>So können sich deine Profilbesucher ein bessere Bild von dir machen. Das erhöht die Chance, deinen Trauerfreund zu finden.</p>
        </div>
    </div>
<?php echo $__env->renderComponent(); ?>

Geh’ heute den nächsten Schritt.

<?php $__env->startComponent('mail::button', ['url' => 'https://www.trosthelden.de', 'color' => 'grey']); ?>
Zur TrostHelden-Website
<?php echo $__env->renderComponent(); ?>

Solltest du Fragen haben, schreibe uns gern an E-Mail an service@trosthelden.de.<br><br>
Wir wünschen dir viele gute Erfahrungen auf diesem neuen Weg deiner Trauerarbeit,<br><br>
dein TrostHelden-Team

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/introduction/introduction.blade.php ENDPATH**/ ?>