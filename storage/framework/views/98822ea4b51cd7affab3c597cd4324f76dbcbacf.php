<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('Cash Balance')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('Cash Balance')); ?><?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4">

                            <div class="col-sm-auto">
                                <div>

                               <img src=" <?php echo e(asset('assets/images/qr_code.jpg')); ?>" class="rounded avatar-lg">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <p><?php echo e(__('Cash Balance description')); ?></p> </div>
                                </div>
                            </div>

                        </div>
                        <div class="card border card-border-info">
                        <div class="card card-body">
                            <div class="d-flex mb-4 align-items-center">
                                <div class="flex-shrink-0">
                                    <img src="<?php echo e(URL::asset('assets/images/paytabs.jpeg')); ?>" alt="">
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h5 class="card-title mb-1"><?php echo e(__('Paytabs_Payment_Gateway')); ?></h5>
                                </div>
                            </div>
                            <img src="<?php echo e(URL::asset('assets/images/pay.jpeg')); ?>" alt="" style="height: 60px;
    width: 120px;">
                            <div class="row g-4">
                                <div class="col-lg-6">

                                    <div class="input-group">

                                        <input aria-describedby="simulate" type="number"  class="form-control" id="ammount1" required>
                                        <span class="input-group-text">$</span></div>
                                    <div class="input-group">
                                        <input aria-describedby="simulate" type="number"  class="form-control" id="ammount2" required>
                                        <span class="input-group-text" >SAR</span></div>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button" id="simulate1"><?php echo e(__('simulate')); ?></button>
                                        <button class="btn btn-success" type="button" id="validate" data-route-url="<?php echo e(route('paytabs', app()->getLocale())); ?>"><?php echo e(__('validate')); ?></button>
                                    </div>





                                </div>
                            </div>
                        </div></div>


                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none "><?php echo e(__('Ref')); ?></th>
                                <th style=" border: none "><?php echo e(__('Date')); ?></th>
                                <th style=" border: none "><?php echo e(__('Operation Designation')); ?></th>
                                <th style=" border: none "><?php echo e(__('Description')); ?></th>

                                <th style=" border: none "><?php echo e(__('Value')); ?></th>
                                <th style=" border: none "><?php echo e(__('Balance')); ?></th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

</div>
<script>

    $(document).on("click", "#simulate1", function () {
         console.log($("#ammount1").val());
        $('#ammount2').val($("#ammount1").val()* <?php echo e(usdToSar()); ?>);
        });
    $(document).on("click", "#validate", function () {
        var amount = $('#ammount2').val();
        var routeUrl = "<?php echo e(route('paytabs', app()->getLocale())); ?>";
        // Ajouter le montant comme paramètre de requête
        routeUrl += "?amount=" + encodeURIComponent(amount);
        window.location.href = routeUrl;

    });



</script>








<?php /**PATH C:\Users\ghazi\Documents\GitHub\2earnprod\resources\views/livewire/user-balance-c-b.blade.php ENDPATH**/ ?>