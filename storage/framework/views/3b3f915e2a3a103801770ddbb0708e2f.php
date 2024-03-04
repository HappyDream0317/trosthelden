<?php
    /**
    * @var $user App\User
    */
?>

<?php $__env->startComponent('mail::message'); ?>
    <p>username: <?php echo e($user->nickname); ?></p>
    <p>id: <?php echo e($user->id); ?></p>
    <p>contract termination date: <?php echo e($endDate->format("d.m.Y")); ?> </p>
    <p>reason: <?php echo e($reason); ?></p>
    <br>
    <br>
    <p><b>ERROR: "<?php echo e($message); ?>"</b></p>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/payments/termination/internal/error.blade.php ENDPATH**/ ?>