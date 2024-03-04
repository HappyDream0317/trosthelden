<?php
    /**
    * @var $user App\User
    */
?>

<?php $__env->startComponent('mail::message'); ?>
    <p>
        Hallo <?php echo e($user->firstname ?? $customer->FirstName); ?> <?php echo e($user->lastname ?? $customer->LastName); ?>,
    </p>
    <br>
    <p>
        deine außerordentliche Kündigung vom <?php echo e(Carbon\Carbon::now()->format("d.m.Y")); ?> um <?php echo e(Carbon\Carbon::now()->format('H:i')); ?> ist bei uns eingegangen.
    </p>
    <p>
        Dein Benutzername: <?php echo e($user->nickname); ?> (<?php echo e($user->id); ?>)
    </p>
    <p>
        Deine Mitgliedschaft: <?php echo e($product); ?> <?php echo e($product > 1 ? "Monate" : "Monat"); ?> TrostHelden-Mitgliedschaft
    </p>
    <p>
        Du hast folgenden Grund für die außerordentliche Kündigung angegeben: <?php echo e($reason); ?>

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
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/payments/termination/user/extraordinary.blade.php ENDPATH**/ ?>