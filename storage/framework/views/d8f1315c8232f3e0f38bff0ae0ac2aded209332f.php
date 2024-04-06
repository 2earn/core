
<style>
    .btn-ghost-danger {
        color: #bc34b6!important;

    }
    .btn-ghost-danger:active, .btn-ghost-danger:focus, .btn-ghost-danger:hover{color: #bc34b6!important;
        background-color: rgba(188, 52, 182, 0.1)!important;}
    .btn-ghost-secondary {
        color: #464fed!important;
    }
    .btn-ghost-secondary:active, .btn-ghost-secondary:focus, .btn-ghost-secondary:hover { color: #464fed!important;
        background-color: rgba(70, 79, 237, 0.1)!important;
    }
</style>
<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('history')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('UsersList')); ?> <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card crm-widget">
                        <div class="card-body p-0">
                            <div class="row row-cols-md-3 row-cols-1">
                                <div class="col col-lg border-end">
                                    <div class="py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">Cash Balance
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo e(getUserListCards()[0]); ?>"><?php echo e(getUserListCards()[0]); ?></span></h2>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0"><i class="ri-building-line align-bottom"></i>
                                            <?php echo e(number_format(getAdminCash()[0],2)); ?> <span class="ms-2"><i class="ri-map-pin-2-line align-bottom"></i> <?php echo e(number_format(getUserListCards()[0]-getAdminCash()[0],2)); ?></span></p>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg border-end">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">BFS </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-shopping-cart-2-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo e(getUserListCards()[1]); ?>"><?php echo e(getUserListCards()[1]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg border-end">
                                    <div class="mt-3 mt-md-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">
                                            Discount Balance
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class=" ri-percent-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo e(getUserListCards()[2]); ?>"><?php echo e(getUserListCards()[2]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg border-end">
                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">
                                            SMS Balance
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-message-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e(getUserListCards()[3]); ?>"><?php echo e(getUserListCards()[3]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg">
                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">
                                            Shares Sold

                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-stackshare-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e(getUserListCards()[4]); ?>"><?php echo e(getUserListCards()[4]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg">
                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">
                                            Shares Revenue
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-swap-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo e(getUserListCards()[5]); ?>"><?php echo e(getUserListCards()[5]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                                <div class="col col-lg">
                                    <div class="mt-3 mt-lg-0 py-4 px-3">
                                        <h5 class="text-muted text-uppercase fs-13">
                                            Cash Flow
                                        </h5>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="ri-exchange-funds-line display-6 text-muted"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo e(getUserListCards()[5]+getUserListCards()[0]); ?>"><?php echo e(getUserListCards()[5]+getUserListCards()[0]); ?></span></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                    </div>
                    <div class="card-body table-responsive">
                        <table id="users-list" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead  class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none;text-align: center; "><?php echo e(__('created at')); ?></th>
                                <th style=" border: none ;text-align: center; "><?php echo e(__('pays')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Phone')); ?></th>

                                <th style=" border: none;"><?php echo e(__('Name')); ?></th>
                                <th style=" border: none;"><?php echo e(__('SoldeCB')); ?></th>
                                <th style=" border: none;"><?php echo e(__('SoldeBFS')); ?></th>
                                <th style=" border: none;"><?php echo e(__('SoldeDB')); ?></th>
                                <th style=" border: none;"><?php echo e(__('SoldeSMS')); ?></th>
                                <th style=" border: none;"><?php echo e(__('SoldeSHARES')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('otp')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Password')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('Action')); ?></th>

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

            <!-- Grids in modals -->

            <div class="modal fade" id="AddCash" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalgridLabel"><?php echo e(__('Transfert Cash')); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:void(0);">
                                <div class="row g-3">
                                    <div class="col-xxl-6">

                                            <!-- Basic example -->
                                            <div class="input-group">
                                                <span class="input-group-text" ><img  id="userlist-country" alt="" class="avatar-xxs me-2"></span>
                                                <input type="text" class="form-control"  disabled id="userlist-phone" aria-describedby="basic-addon1">
                                            </div>

                                    </div><!--end col-->


                                    <div class="col-xxl-6">
                                        <div class="input-group">
                                            <input  id ="userlist-reciver" type="hidden">
                                            <input type="number" class="form-control" id="ammount">
                                            <span class="input-group-text">$</span>

                                        </div>
                                    </div><!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                                            <button type="button"   id="userlist-submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
                                        </div>
                                    </div><!--end col-->
                                </div><!--end row-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <script>
            function createOrUpdateDataTable(data) {
                // Détruire le DataTable existant s'il y en a un
                if ($.fn.DataTable.isDataTable('#ub_table_list')) {
                    $('#ub_table_list').DataTable().destroy();
                }

                // Créer un nouveau DataTable avec les nouvelles données
                $('#ub_table_list').DataTable({
                    ordering: true,
                    retrieve: true,
                    searching: false,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[1, 'asc']],
                    "processing": true,
                    "data": data,
                    "columns": [
                        {data: 'ref'},
                        {data: 'Date'},
                        {data: 'Designation'},
                        {data: 'Description'},
                        {data: 'value', className: window.classAl},
                        {data: 'balance', className: window.classAl},
                    ],
                    "columnDefs": [
                        {
                            "targets": [4],
                            render: function (data, type, row) {
                                if (row.value < 0) {
                                    return '<span class="badge bg-danger text-end">' + data + '</span>';
                                } else {
                                    return '<span class="badge bg-success text-end">' + data + '</span>';
                                }
                            },
                            className: window.classAl,
                        },
                        {
                            "targets": [5],
                            className: window.classAl,
                        }
                    ],

                });
            }

            $(document).on("click", ".cb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');

                //console.log(reciver);
                //console.log(amount);
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "<?php echo e(route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1'])); ?>";
                //console.log(window.url);
                window.url = window.url.replace('idUser1', reciver);
                //console.log(window.url);
                window.url = window.url.replace('idamount1', amount);
                //console.log(window.url);

                $(document).ready(function(){
                    //console.log(window.url);
                    $.getJSON(window.url, function(data) {
                        //console.log("data");
                        //console.log(data);
                        createOrUpdateDataTable(data); // Appeler la fonction pour créer ou mettre à jour le DataTable avec les nouvelles données
                    });
                });
            });
            $(document).on("click", ".bfs", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');

                //console.log(reciver);
                //console.log(amount);
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "<?php echo e(route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1'])); ?>";
                //console.log(window.url);
                window.url = window.url.replace('idUser1', reciver);
                //console.log(window.url);
                window.url = window.url.replace('idamount1', amount);
                //console.log(window.url);

                $(document).ready(function(){
                    //console.log(window.url);
                    $.getJSON(window.url, function(data) {
                       //console.log("data");
                        //console.log(data);
                        createOrUpdateDataTable(data); // Appeler la fonction pour créer ou mettre à jour le DataTable avec les nouvelles données
                    });
                });
            });
            $(document).on("click", ".db", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');

                //console.log(reciver);
                //console.log(amount);
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "<?php echo e(route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1'])); ?>";
                //console.log(window.url);
                window.url = window.url.replace('idUser1', reciver);
                //console.log(window.url);
                window.url = window.url.replace('idamount1', amount);
                //console.log(window.url);

                $(document).ready(function(){
                    //console.log(window.url);
                    $.getJSON(window.url, function(data) {
                        //console.log("data");
                       // console.log(data);
                        createOrUpdateDataTable(data); // Appeler la fonction pour créer ou mettre à jour le DataTable avec les nouvelles données
                    });
                });
            });
            $(document).on("click", ".smsb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');

                //console.log(reciver);
                //console.log(amount);
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "<?php echo e(route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1'])); ?>";
                //console.log(window.url);
                window.url = window.url.replace('idUser1', reciver);
                //console.log(window.url);
                window.url = window.url.replace('idamount1', amount);
                //console.log(window.url);

                $(document).ready(function(){
                    //console.log(window.url);
                    $.getJSON(window.url, function(data) {
                        //console.log("data");
                        //console.log(data);
                        createOrUpdateDataTable(data); // Appeler la fonction pour créer ou mettre à jour le DataTable avec les nouvelles données
                    });
                });
            });
        </script>
            <script>
                function createOrUpdateDataTablesh(data) {
                    // Détruire le DataTable existant s'il y en a un
                    if ($.fn.DataTable.isDataTable('#ub_table_listsh')) {
                        $('#ub_table_listsh').DataTable().destroy();
                    }

                    // Créer un nouveau DataTable avec les nouvelles données
                    $('#ub_table_listsh').DataTable({
                        ordering: true,
                        retrieve: true,
                        searching: false,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "order": [[1, 'asc']],
                        "processing": true,
                        "data": data,
                        "columns": [
                            {data: 'Date'},
                            {data: 'value'},
                            {data: 'gifted_shares'},
                            {data: 'total_shares'},
                            {data: 'total_price'},
                            {data: 'present_value'},
                            {data: 'current_earnings'},

                        ],
                        "columnDefs": [

                        ],

                    });
                }

                $(document).on("click", ".sh", function () {
                    let reciver = $(this).data('reciver');
                    let amount = $(this).data('amount');

                    //console.log(reciver);
                    //console.log(amount);
                    $('#balances-amountsh').attr('value', amount);
                    $('#balances-reciversh').attr('value', reciver);

                    window.url = "<?php echo e(route('API_SharesSolde_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1'])); ?>";
                    //console.log(window.url);
                    window.url = window.url.replace('idUser1', reciver);
                    //console.log(window.url);


                    $(document).ready(function(){
                        //console.log(window.url);
                        $.getJSON(window.url, function(data) {
                            //console.log("data");
                            //console.log(data);
                            createOrUpdateDataTablesh(data); // Appeler la fonction pour créer ou mettre à jour le DataTable avec les nouvelles données
                        });
                    });
                });

            </script>

            <div class="modal fade modal-xl" id="detail" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalgridLabel"><?php echo e(__('Transfert Cash')); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body table-responsive">
                                    <input id="balances-reciver" type="hidden" >
                                    <input id="balances-amount" type="hidden">







                                    <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table_list" style="width: 100%">
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

                    </div>
                </div>
            </div>
            <div class="modal fade modal-xl" id="detailsh" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalgridLabelsh"><?php echo e(__('Transfert Cash')); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-body table-responsive">
                                    <input id="balances-reciversh" type="hidden" >
                                    <input id="balances-amountsh" type="hidden">







                                    <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table_listsh" style="width: 100%">
                                        <thead class="table-light">
                                        <tr class="head2earn  tabHeader2earn" >
                                            <th style=" border: none ;text-align: center;"><?php echo e(__('date_purchase')); ?></th>
                                            <th style=" border: none;"><?php echo e(__('number_of_shares')); ?></th>
                                            <th style=" border: none;"><?php echo e(__('gifted_shares')); ?></th>
                                            <th style=" border: none ;text-align: center; "><?php echo e(__('total_shares')); ?></th>
                                            <th style=" border: none;text-align: center; "><?php echo e(__('total_price')); ?></th>
                                            <th style=" border: none;text-align: center; "><?php echo e(__('present_value')); ?></th>
                                            <th style=" border: none;text-align: center; "><?php echo e(__('current_earnings')); ?></th>

                                        </tr>
                                        </thead>
                                        <tbody class="body2earn">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        <!--end row-->

</div>
<?php /**PATH C:\Users\ghazi\Documents\GitHub\2earnprod\resources\views/livewire/user-list.blade.php ENDPATH**/ ?>