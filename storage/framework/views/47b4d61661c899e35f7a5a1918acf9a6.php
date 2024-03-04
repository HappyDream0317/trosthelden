<?php $__env->startSection('title', '| Group Kategorien'); ?>

<?php $__env->startSection('content'); ?>

<h1>Boot Kategorien</h1>
<hr>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Beschreibung</th>
            <th></th>
            <th>Max. Größe</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>

                <td><?php echo e($category->name); ?></td>
                <td><?php echo e($category->description); ?></td>
                <td><?php echo e($category->icon); ?></td>
                <td><?php echo e($category->max_size); ?></td>
                <td>
                    <a href="<?php echo e(URL::to('admin/bootcategories/'.$category->id.'/edit')); ?>" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['admin.bootcategories.destroy', $category->id] ]); ?>

                    <?php echo Form::submit('Delete', ['class' => 'btn btn-danger']); ?>

                    <?php echo Form::close(); ?>


                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

    </table>
</div>

<a href="<?php echo e(URL::to('admin/bootcategories/create')); ?>" class="btn btn-success">Trostboot Kategorie hinzufügen</a>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/admin/boot_categories/index.blade.php ENDPATH**/ ?>