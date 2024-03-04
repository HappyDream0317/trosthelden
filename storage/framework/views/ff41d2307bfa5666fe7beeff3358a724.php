<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('/apple-touch-icon.png?v=gAXOOdnwKo')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('/favicon-32x32.png?v=gAXOOdnwKo')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('/favicon-16x16.png?v=gAXOOdnwKo')); ?>">
    <link rel="manifest" href="<?php echo e(asset('/site.webmanifest?v=gAXOOdnwKo')); ?>">
    <link rel="mask-icon" href="<?php echo e(asset('/safari-pinned-tab.svg?v=gAXOOdnwKo')); ?>" color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo e(asset('/favicon.ico?v=gAXOOdnwKo')); ?>">
    <meta name="msapplication-TileColor" content="#e3e3d0">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TRR5BTK');</script>
    <!-- End Google Tag Manager -->

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
    </script>

    <?php if(Robots::isNoIndex()): ?>
        <meta name="robots" content="noindex" />
    <?php endif; ?>

    <title><?php echo e(config('app.name', 'TrostHelden')); ?></title>
    <link href="<?php echo e(mix('css/app.css')); ?>" rel="stylesheet">

    <script type="text/javascript" src="//app.storyblok.com/f/storyblok-latest.js"></script>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TRR5BTK"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="app">
    <app></app>
</div>
<script src="<?php echo e(mix('js/app.js')); ?>" defer data-cookieconsent="ignore"></script>
</body>
</html>
<?php /**PATH /home/470763.cloudwaysapps.com/tudjznbtye/public_html/releases/a77e6f4d01eef97a18ce7ecea393eb6cf2d8a9c3/resources/views/layouts/vueapp.blade.php ENDPATH**/ ?>