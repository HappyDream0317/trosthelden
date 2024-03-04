<?php
    /**
    * @var $title string
    * @var $post_url string
    * @var $post_title string
    * @var $comment string
    * @var $reporter_username string
    * @var $reporter_id int
    * @var $reported_user_username string
    * @var $reported_user_id int
    * @var $reason string
    */
?>

<?php $__env->startComponent('mail::message'); ?>
# <?php echo e($title); ?>


<?php $__env->startComponent('mail::button', ['url' => $post_url]); ?>
    Zum gemeldeten Beitrag
<?php echo $__env->renderComponent(); ?>

Einzelheiten:

- Titel des Beitrags: <?php echo e($post_title); ?>

<?php if(isset($comment)): ?>
- Kommentar: <?php echo $comment; ?>

<?php endif; ?>
- Meldender Benutzer: <?php echo e(sprintf("%s (%s)", $reporter_username, $reporter_id)); ?>

- Gemeldeter Benutzer: <?php echo e(sprintf("%s (%s)", $reported_user_username, $reported_user_id)); ?>

- Text aus dem Eingabefeld: <?php echo e($reason); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/emails/report/post_report.blade.php ENDPATH**/ ?>