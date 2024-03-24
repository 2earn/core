
<?php
$bg="#464fed" ;

if(\Request::getRequestUri()=="/".app()->getLocale()."/user_balance_bfs")
    {
        $bg = '#bc34b6'  ;
    }
elseif (\Request::getRequestUri()=="/".app()->getLocale()."/user_balance_db")
{
 $bg = '#009fe3';
}
?>
<?php if (isset($component)) { $__componentOriginal85f966f1b5de8551aa930b6f61c6100ede97428e = $component; } ?>
<?php $component = App\View\Components\PageTitle::resolve(['bg' => ''.e($bg).'','pageTitle' => ''.e($title).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-title'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\PageTitle::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85f966f1b5de8551aa930b6f61c6100ede97428e)): ?>
<?php $component = $__componentOriginal85f966f1b5de8551aa930b6f61c6100ede97428e; ?>
<?php unset($__componentOriginal85f966f1b5de8551aa930b6f61c6100ede97428e); ?>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\2earn\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>