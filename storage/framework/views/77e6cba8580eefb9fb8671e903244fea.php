<?php
    /**
    * @var $user App\User
    * @var $newMessageCount integer
    * @var $chatUrl string
    */
?>

<?php $__env->startComponent('mail::message'); ?>
# Es gibt Neuigkeiten von deinem Trauerfreund

Hallo <?php echo e($user->nickname); ?>,
schau gleich mal in deine Nachrichten-Übersicht. Denn du hast <?php echo e($newMessageCount); ?> neue Nachricht(en) in deinem TrostHelden-Postfach!
<?php $__env->startComponent('mail::button', ['url' => $chatUrl, 'color' => 'gold']); ?>
Meine Nachrichten
<?php echo $__env->renderComponent(); ?>

Wir wünschen euch einen wertvollen Austausch.<br>
Herzliche Grüße,<br>
dein TrostHelden-Team

<hr style="border: 1px solid #efefef;">
<p>Diese E-Mail wird direkt von <a href="https://www.trosthelden.de">TrostHelden.de</a> versendet. Du kannst auf unserer <a href="https://app.trosthelden.de/notifications">Einstellungsseite</a> festlegen, welche Benachrichtigungen du von uns erhalten möchtest.</p>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/notifications/chat_message_resume.blade.php ENDPATH**/ ?>