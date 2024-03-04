<?php
    /**
    * @var $user App\User
    * @var $friend App\User
    * @var $friendRequestUrl string
    */
?>

<?php $__env->startComponent('mail::message'); ?>
# Ein Trauerfreund für dich persönlich

Hallo <?php echo e($user->nickname); ?>,
es gibt eine Trauerfreund-Anfrage für dich! Dieser besondere Mensch könnte perfekt zu dir passen.

Schau dir doch einmal das Profil dieses TrostHelden-Mitglieds an. Vielleicht hast du bereits eine Nachricht zusammen mit der Anfrage erhalten.

Dieser Trauerfreund spricht dich an? Dann akzeptiere einfach seine Anfrage, damit ihr euch näher kennenlernen könnt.
<?php $__env->startComponent('mail::button', ['url' => $friendRequestUrl, 'color' => 'gold']); ?>
Trauerfreund-Anfragen
<?php echo $__env->renderComponent(); ?>

Wir wünschen dir von Herzen alles Gute,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/notifications/friend_request.blade.php ENDPATH**/ ?>