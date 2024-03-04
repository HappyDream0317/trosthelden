<?php $__env->startSection('title', '| Edit Role'); ?>

<?php $__env->startSection('content'); ?>

<h1><i class='fa fa-key'></i> Edit Role: <?php echo e($role->name); ?></h1>
<hr>

<?php echo e(Form::model($role, array('route' => array('admin-roles.update', $role->id), 'method' => 'PUT'))); ?>


<div class="form-group">
    <?php echo e(Form::label('name', 'Role Name')); ?>

    <?php echo e(Form::text('name', null, array('class' => 'form-control'))); ?>

</div>

<h5><b>Assign Permissions</b></h5>
<?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php echo e(Form::checkbox('permissions[]',  $permission->id, $role->permissions )); ?>

    <?php echo e(Form::label($permission->name, ucfirst($permission->name))); ?><br>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<br>
<?php echo e(Form::submit('Edit', array('class' => 'btn btn-primary'))); ?>


<?php echo e(Form::close()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/admin/boot_categories/edit.blade.php ENDPATH**/ ?>