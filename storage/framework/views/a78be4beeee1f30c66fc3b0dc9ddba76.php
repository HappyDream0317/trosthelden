<?php $__env->startComponent('mail::message'); ?>

<?php if(! empty($greeting)): ?>
# <?php echo e($greeting); ?>

<?php else: ?>
<?php if($level === 'error'): ?>
# <?php echo app('translator')->get('Whoops!'); ?>
<?php else: ?>
# <?php echo app('translator')->get('Hallo!'); ?>
<?php endif; ?>
<?php endif; ?>


<?php $__currentLoopData = $introLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php echo e($line); ?>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if(isset($actionText)): ?>
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
<?php $__env->startComponent('mail::button', ['url' => $actionUrl, 'color' => $color]); ?>
<?php echo e($actionText); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>


<?php $__currentLoopData = $outroLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php echo e($line); ?>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if(! empty($salutation)): ?>
<?php echo e($salutation); ?>

<?php else: ?>
<?php echo app('translator')->get(''); ?><br>
Ihr TrostHelden-Team
<?php endif; ?>


<?php if(isset($actionText)): ?>
<?php $__env->slot('subcopy'); ?>
<?php echo app('translator')->get(
    "Bei Problemen mit dem Button \":actionText\" können Sie auch den folgenden Link nutzen:",
    [
        'actionText' => $actionText,
    ]
); ?> <span class="break-all">[<?php echo e($displayableActionUrl); ?>](<?php echo e($actionUrl); ?>)</span>
<?php $__env->endSlot(); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/vendor/notifications/email.blade.php ENDPATH**/ ?>