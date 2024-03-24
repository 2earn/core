<?php echo $__env->yieldContent('css'); ?>
<?php if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl'): ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="<?php echo e(URL::asset('assets/js/layout.js')); ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo e(URL::asset('assets/css/bootstrap.rtl.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="<?php echo e(URL::asset('assets/css/icons.rtl.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="<?php echo e(URL::asset('assets/css/app.rtl.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- custom Css-->
    <link href="<?php echo e(URL::asset('assets/css/custom.rtl.css')); ?>" rel="stylesheet" type="text/css"/>
<?php else: ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="<?php echo e(URL::asset('assets/js/layout.js')); ?>"></script>
    <!-- Bootstrap Css -->
    <link href="<?php echo e(URL::asset('assets/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="<?php echo e(URL::asset('assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="<?php echo e(URL::asset('assets/css/app.min.css')); ?>" rel="stylesheet" type="text/css"/>
    <!-- custom Css-->
 
    <link href="<?php echo e(URL::asset('assets/css/custom.min.css')); ?>"  rel="stylesheet" type="text/css" />
     
 
   
 
<?php endif; ?>

<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/layouts/head-css.blade.php ENDPATH**/ ?>