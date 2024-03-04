<?php
    /**
    * @var $user App\User
    * @var $friend App\User
    * @var $chatUrl string
    */
?>

<?php $__env->startComponent('mail::message'); ?>
# Du hast einen neuen Trauerfreund

Hallo <?php echo e($user->nickname); ?>,
wir haben gute Nachrichten für dich!
<?php echo e($friend->nickname); ?> hat deine Trauerfreund-Anfrage bestätigt. Schreib deinem neuen Trauerfreund doch gleich mal, denn ihr habt viele Gemeinsamkeiten!
<?php $__env->startComponent('mail::button', ['url' => $chatUrl, 'color' => 'gold']); ?>
Nachrichten
<?php echo $__env->renderComponent(); ?>

Herzliche Grüße,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/notifications/friend_request_confirmation.blade.php ENDPATH**/ ?>