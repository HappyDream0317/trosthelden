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
        deine fristgerechte Kündigung vom <?php echo e(Carbon\Carbon::now()->format("d.m.Y")); ?> um <?php echo e(Carbon\Carbon::now()->format('H:i')); ?> Uhr ist bei uns eingegangen.
    </p>
    <p>
        Dein Benutzername: <?php echo e($user->nickname); ?> (<?php echo e($user->id); ?>)
    </p>
    <p>
        Deine Mitgliedschaft: <?php echo e($product); ?> <?php echo e($product > 1 ? "Monate" : "Monat"); ?> TrostHelden-Mitgliedschaft
    </p>
    <p>
        Dein Vertrag wird somit zum <?php echo e($endDate->format("d.m.Y")); ?> fristgerecht gekündigt. Solltest du noch Fragen haben, sende gern eine E-Mail mit deinem Benutzernamen an <a href="mailto:abo@trosthelden.de">abo@trosthelden.de</a>.
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
<?php echo $__env->renderComponent(); ?><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/payments/termination/user/proper.blade.php ENDPATH**/ ?>