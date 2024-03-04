<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo $__env->make('documents.components.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
<header>
    <?php echo $__env->make('documents.components.logo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('documents.components.separator', ['num' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</header>
<footer>
    <?php echo $__env->make('documents.components.separator', ['num' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('documents.components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</footer>
<main>

    <?php echo $__env->make('documents.components.title', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('documents.components.quote', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('documents.components.intro', ['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('documents.components.code', ['code' => $code], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="page_break"></div>

    <?php echo $__env->make('documents.components.instructions', [
        'product' => $product
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</main>

</body>
</html><?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/documents/code.blade.php ENDPATH**/ ?>