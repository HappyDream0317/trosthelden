<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('E-Mail Bestätigung')); ?></div>

                <div class="card-body">
                    <?php if(session('resent')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(__('Eine neue E-Mail zur Bestätigung deiner Adresse wurde versendet.')); ?>

                        </div>
                    <?php endif; ?>

                    <?php echo e(__('Bevor du fortfährst, prüfe bitte deine E-Mail Adresse..')); ?>

                    <?php echo e(__('Wenn du keine E-Mail erhalten hast')); ?>, <a href="<?php echo e(route('verification.resend')); ?>"><?php echo e(__('klicke hier, um eine neue E-Mail zu versenden')); ?></a>.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/auth/verify.blade.php ENDPATH**/ ?>