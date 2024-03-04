<?php
    /**
    * @var $title string
    * @var $novaUrl string
    * @var $user \App\User
    */
?>

<?php $__env->startComponent('mail::message'); ?>
# <?php echo e($title); ?>


<?php $__env->startComponent('mail::button', ['url' => $novaUrl]); ?>
    Zum User in Nova
<?php echo $__env->renderComponent(); ?>

<?php echo e($user->id); ?>

<?php echo e($user->nickname); ?>


<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/delete_user/delete_user.blade.php ENDPATH**/ ?>