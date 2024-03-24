<!DOCTYPE html>
<html dir="<?php echo e(config('app.available_locales')[app()->getLocale()]['direction']); ?>"
      lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="antialiased">
<div>
    <img src="<?php echo e(asset('assets/images/2earn.png')); ?>" sizes="60x40" alt="">
</div>
<p style="font-family: open-sans !important"><?php echo e(__('Dear')); ?> </p>
<span style="margin:2px;font-size: 16px;color: blue;"><?php echo e($data); ?></span>
<div>
    <span><?php echo e(__('best regards')); ?> </span>
</div>
<div>
    <span><b><a href="" style="color: blue !important">2Earn.cash</a></b></span>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/pwd_email.blade.php ENDPATH**/ ?>
