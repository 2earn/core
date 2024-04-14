<!doctype html >
<html dir="<?php echo e(config('app.available_locales')[app()->getLocale()]['direction']); ?>"
      lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-layout="vertical" data-topbar="light"
      data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none" data-preloader="disable"
      id="HTMLMain"
>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $__env->yieldContent('title'); ?>| 2Earn.cash</title>


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('assets/images/favicon.ico')); ?>">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/Styles/intlTelInput.css')); ?>">
    <script src="<?php echo e(asset('assets/js/intlTelInput.js')); ?>"></script>
    <?php echo $__env->make('layouts.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(URL::asset('assets/libs/filepond/filepond.min.css')); ?>" type="text/css"/>
    <link rel="stylesheet"
          href="<?php echo e(URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css')); ?>">


    <script src="<?php echo e(mix('js/turbo.js')); ?>" defer></script>

    <?php echo $__env->make('layouts.vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js "></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <?php echo \Livewire\Livewire::styles(); ?>

</head>


<script>
    var fromLogin = '<?php echo e(Session::has('fromLogin')); ?>';

    if (fromLogin) {
        // alert('er');
        location.reload();
    }
</script>


<?php $__env->startSection('body'); ?>
    <?php echo $__env->make('layouts.body', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldSection(); ?>


<!-- Begin page -->
<div id="layout-wrapper">
    <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('top-bar', [])->html();
} elseif ($_instance->childHasBeenRendered('kcMM6DK')) {
    $componentId = $_instance->getRenderedChildComponentId('kcMM6DK');
    $componentTag = $_instance->getRenderedChildComponentTagName('kcMM6DK');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('kcMM6DK');
} else {
    $response = \Livewire\Livewire::mount('top-bar', []);
    $html = $response->html();
    $_instance->logRenderedChild('kcMM6DK', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
<?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php echo $__env->yieldContent('content'); ?>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

<?php echo $__env->make('layouts.customizer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- JAVASCRIPT -->


<?php echo \Livewire\Livewire::scripts(); ?>



<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>

<script src="<?php echo e(mix('js/turbo.js')); ?>" defer></script>
<script src="<?php echo e(URL::asset('/assets/libs/dropzone/dropzone.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/pages/crypto-kyc.init.js')); ?>"></script>

<script src="<?php echo e(URL::asset('assets/libs/filepond/filepond.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js')); ?>">
</script>
<script
    src="<?php echo e(URL::asset('assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')); ?>">
</script>
<script
    src="<?php echo e(URL::asset('assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js')); ?>">
</script>
<script src="<?php echo e(URL::asset('assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js')); ?>"></script>


<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>" defer></script>


});
</script>
<script>


    var $_lang = (function () {
        let json;

        $.ajax({
            type: "GET",
            url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/ar.json",
            dataType: "json",
            success: function (data) {
                json = data;
            }
        });

        return {
            getjson: function () {
                if (json) return json;
                // else show some error that it isn't loaded yet;
            }
        };
    })();
    $('#HistoryNotificationTable').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        var that = $('#HistoryNotificationTable').DataTable();
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_HistoryNotification',app()->getLocale())); ?>",
            "columns": [
                {data: 'reference'},
                {data: 'send'},
                {data: 'receiver'},
                {data: 'action'},
                {data: 'date'},
                {data: 'type'},
                {data: 'responce'},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );
    $('#userManager_table').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        var that = $('#userManager_table').DataTable();
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_usermanager',app()->getLocale())); ?>",
            "columns": [
                {data: 'N'},
                {data: 'idUser'},
                {data: 'status'},
                {data: 'registred_from'},
                {data: 'fullphone_number'},
                {data: 'LatinName'},
                {data: 'ArabicName'},
                {data: 'lastOperation'},
                {data: 'country'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );

    $('#contacts_table').DataTable(
        {
            retrieve: true,
            "processing": true,
            paging: true,
            "aLengthMenu": [[5, 10, 50], [5, 10, 50]],
            //recherche avec entre key
            // search: {
            //     return: true
            // },
            "ajax": "<?php echo e(route('API_UserContacts',app()->getLocale())); ?>",
            "columns": [
                {"data": "name"},
                {"data": "lastName"},
                {"data": "mobile"},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );
    $('#userPurchase_table').DataTable(
        {
            "ordering": false,
            retrieve: true,
            "colReorder": false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        if ($.fn.dataTable.isDataTable('#countries_table')) {

                            var that = $('#userPurchase_table').DataTable();
                        }
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that

                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "order": [[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
            search: {
                return: true
            },
            autoWidth: false,
            bAutoWidth: false,
            "ajax": "<?php echo e(route('API_userPurchase', app()->getLocale() )); ?>",
            "columns": [
                {data: 'DateAchat'},
                {data: 'ReferenceAchat'},
                {data: 'item_title'},
                {data: 'nbrAchat'},
                {data: 'Amout'},
                {data: 'invitationPurshase'},
                {data: 'visit'},
                {data: 'PRC_BFS'},
                {data: 'PRC_CB'},
                {data: 'CashBack_BFS'},
                {data: 'CashBack_CB'},
                {data: 'Economy'}


                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ],


            /* "language": {
                 "url": $_lang
             }*/
        }
    );


    $('#userBalanceSMS_table').DataTable(
        {
            "ordering": false,
            retrieve: true,
            "colReorder": false,
            // dom: 'Bfstrip',
            // buttons: [
            //     'csv', 'excel'
            // ],
            "orderCellsTop": true,
            "fixedHeader": true,
            /* initComplete: function () {
                 // Apply the search
                 this.api()
                     .columns()
                     .every(function () {
                         // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                         //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                         // }
                         if ($.fn.dataTable.isDataTable('#countries_table')) {

                             var that = $('#userBalanceSMS_table').DataTable();
                         }
                         $('input', this.footer()).on('keydown', function (ev) {
                             if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                 that

                                     .search(this.value)
                                     .draw();
                             }
                         });
                     });
             },*/
            "order": [[1, 'asc']],
            "processing": true,
            "serverSide": false,
            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
            search: {
                return: true
            },
            autoWidth: false,
            bAutoWidth: false,
            "ajax": "<?php echo e(route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'SMS-Balance'])); ?>",
            "columns": [
                {data: 'ranks', "width": "1%"},
                {data: 'Ref'},
                {data: 'Date'},
                {data: 'Designation'},
                {data: 'Description'},
                {data: 'PrixUnitaire'},
                {data: 'value'},
                {data: 'balance'},


            ],
            "columnDefs":
                [
                    {
                        "targets": [6],
                        render: function (data, type, row) {
                            if (data.indexOf('+') == -1)
                                return '<span class="badge bg-danger">' + data + '</span>';
                            else
                                return '<span class="badge bg-success">' + data + '</span>';

                        }
                    }],
            "language": {
                "url": $_lang
            }
        }
    );


    $('#ub_table').DataTable(
        {
            ordering: false,
            retrieve: true,

            // dom: 'Bfstrip',
            // buttons: [
            //     'csv', 'excel'
            // ],
            "orderCellsTop": true,
            "fixedHeader": true,
            /*initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        if ($.fn.dataTable.isDataTable('#countries_table')) {
                            var that = $('#ub_table').DataTable();
                        }
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that

                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },*/
            "order": [[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'cash-Balance'])); ?>",
            "columns": [
                {data: 'Ref'},
                {data: 'Date'},
                {data: 'Designation'},
                {data: 'Description'},

                {data: 'value'},
                {data: 'balance'},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ],

            "columnDefs":
                [
                    {
                        "targets": [4],
                        render: function (data, type, row) {
                            if (data.indexOf('+') == -1)
                                return '<span class="badge bg-danger">' + data + '</span>';
                            else
                                return '<span class="badge bg-success">' + data + '</span>';

                        }
                    }],
            "language": {
                "url": $_lang
            }
        }
    );


    $('#userBalanceDB_table').DataTable(
        {
            "ordering": false,
            retrieve: true,
            "colReorder": false,
            // dom: 'Bfstrip',
            // buttons: [
            //     'csv', 'excel'
            // ],
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        if ($.fn.dataTable.isDataTable('#countries_table')) {

                            var that = $('#userBalanceDB_table').DataTable();
                        }
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that

                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "order": [[1, 'asc']],
            "processing": true,
            "serverSide": true,
            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'Discounts-Balance'])); ?>",
            "columns": [
                {data: 'Ref'},
                {data: 'Date'},
                {data: 'Designation'},
                {data: 'Description'},
                {data: 'value'},
                {data: 'balance'},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );


    // configuratiion page

    $('#SettingsTable').DataTable(
        {
            retrieve: true,
            "colReorder": false,
            "orderCellsTop": false,
            "fixedHeader": true,
            search: {
                return: true
            },
            /*  initComplete: function () {
                  // Apply the search
                  this.api()
                      .columns()
                      .every(function () {
                          // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                          //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                          // }
                          var that = $('#SettingsTable').DataTable();
                          $('#dataTables_filter input', this.footer()).on('keydown', function (ev) {
                              // if (ev.keyCode == 13) {//only on enter keypress (code 13)
                              that
                                  .search(this.value)
                                  .draw();
                              // }
                          });
                      });
              },*/
            "processing": true,
            "aLengthMenu": [[5, 30, 50], [5, 30, 50]],

            "ajax": "<?php echo e(route('API_settings',app()->getLocale())); ?>",
            "columns": [
                {"data": "ParameterName"},
                {"data": "IntegerValue"},
                {"data": "StringValue"},
                {"data": "DecimalValue"},
                {"data": "Unit"},
                {"data": "Automatically_calculated"},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang

            }
        }
    );

    function saveHA() {
        // alert($('#tags').tagsinput('input'));
        // alert($('#tags').val());
        window.livewire.emit('saveHA', $("#tags").val());
    }

    $('#BalanceOperationsTable').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        var that = $('#BalanceOperationsTable').DataTable();
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_BalOperations' ,app()->getLocale())); ?>",
            "columns": [
                {"data": "Designation"},
                {"data": "IO"},
                {"data": "idSource"},
                // { "data": "Mode" },
                {"data": "amountsshortname"},
                // { "data": "Note"   },
                {data: 'MODIFY_AMOUNT'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );
    $('#amountsTable').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        var that = $('#amountsTable').DataTable();
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_Amounts',app()->getLocale())); ?>",
            "columns": [
                {data: 'amountsname'},
                {data: 'amountsshortname'},
                {data: 'amountswithholding_tax'},
                {data: 'amountstransfer'},
                {data: 'amountspaymentrequest'},
                {data: 'amountscash'},
                {data: 'amountsactive'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );


    $('#ActionHistorysTable').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()
                    .columns()
                    .every(function () {
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        if ($.fn.dataTable.isDataTable('#ActionHistorysTable')) {

                            var that = $('#ActionHistorysTable').DataTable();
                        }
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_ActionHistory',app()->getLocale())); ?>",
            "columns": [
                {data: 'title'},
                {data: 'reponce'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "language": {
                "url": $_lang
            }
        }
    );


    table_bfs = $('#ub_table_bfs').DataTable(
        {
            retrieve: true,
            "colReorder": true,
            "orderCellsTop": true,
            "fixedHeader": true,
            initComplete: function () {
                // Apply the search
                this.api()

                    .columns(

                    )
                    .every(function () {
                        var column = this;
                        console.log(column.row(1));
                        column.data().unique().sort().each(function (d, j) {
                            $('.bfs_operation_multiple').append(('<option value="' + d + '">' + d + '</option>'))
                        })
                        // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                        //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                        // }
                        if ($.fn.dataTable.isDataTable('#ub_table_bfs')) {

                            var that = $('#ub_table_bfs').DataTable();
                        }
                        $('input', this.footer()).on('keydown', function (ev) {
                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });


            },
            "processing": true,
            search: {
                return: true
            },
            "ajax": "<?php echo e(route('API_userBFSPurchase',app()->getLocale())); ?>",
            "columns": [


                {data: 'ranks'},
                {data: 'Ref'},
                {data: 'Date'},
                {data: 'Designation'},
                {data: 'Description'},
                {data: 'value'},
                {data: 'balance'},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            "columnDefs":
                [
                    {
                        "targets": [5],
                        render: function (data, type, row) {

                            if (data.indexOf('+') == -1)
                                return '<span class="badge bg-danger">' + data + '</span>';
                            else
                                return '<span class="badge bg-success">' + data + '</span>';

                        }
                    }],
            "language": {
                "url": $_lang
            }
        });


    window.addEventListener('closeModal', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('settingModal'));
        myModal.hide();
        $('#SettingsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })


    // $('.bfs_operation_multiple').select2();
    // $('.bfs_operation_multiple').on('change', function (e) {
    //
    //     var status = $(this).val();
    //     $('.bfs_operation_multiple').val(status)
    //     console.log(status)
    //     //dataTable.column(6).search('\\s' + status + '\\s', true, false, true).draw();
    //     table_bfs.column(3).search(status).draw();
    // });
    window.addEventListener('closeModalOp', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('BoModal'));
        myModal.hide();
        $('#BalanceOperationsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })
    window.addEventListener('closeModalAmounts', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('AmountsModal'));
        myModal.hide();
        $('#amountsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })

    function editSettingFunction(id) {

        window.livewire.emit('editSettingFunction', id);
        // $('#countries_table').DataTable().ajax.reload( );
    }

    function editBOFunction(id) {
        window.livewire.emit('editBOFunction', id);
        // $('#countries_table').DataTable().ajax.reload( );
    }

    function editAmountsFunction(id) {
        window.livewire.emit('editAmountsFunction', id);
        // $('#countries_table').DataTable().ajax.reload( );
    }

    function editHAFunction(id) {
        window.livewire.emit('editHAFunction', id);
        // $('#countries_table').DataTable().ajax.reload( );
    }

    //
    // var input = document.querySelector('input[name=tags]');
    // // initialize Tagify on the above input node reference
    // new Tagify(input)

    // $(document).on('ready turbolinks:load', function () {
    //     alert('turbo');
    // });
    // end gonfiguration page
</script>


<script data-turbolinks-eval="false">

    $(document).on('ready turbolinks:load', function () {

        var ipPhone = document.getElementById("inputPhoneUpdate");
        const myParams = window.location.pathname.split("/");
        const pathPage = myParams[2];
        const pathPageSeg3 = myParams[3];
        var countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phonereg");
        var countryDataLog = window.intlTelInputGlobals.getCountryData(),
            inputlog = document.querySelector("#phone");

        var errorMap = ['<?php echo e(trans('Invalid number')); ?>', '<?php echo e(trans('Invalid country code')); ?>', '<?php echo e(trans('Too shortsss')); ?>', '<?php echo e(trans('Too long')); ?>', '<?php echo e(trans('Invalid number')); ?>'];
        var ipAddContact = document.querySelector("#ipAddContact");
        var ipAdd2Contact = document.querySelector("#ipAdd2Contact");
        var ipUpdatePhoneAd = document.querySelector("#inputPhoneUpdateAd");
        var ipNumberContact = document.querySelector("#inputNumberContact");
        if (pathPage == 'Account') {

            ipPhone.innerHTML =
                "<input type='tel' data-turbolinks-permanent name='mobileUpPhone' id='phoneUpPhone' class='form-control' onpaste='handlePaste(event)'>" +
                "  <span id='valid-msg'   class='invisible'>✓ Valid</span><span id='error-msg' class='hide'></span>" +
                " <input type='hidden' name='fullnumberUpPhone' id='outputUpPhone' value='hidden' class='form-control'> " +
                " <input type='hidden' name='ccodeUpPhone' id='ccodeUpPhone'  ><input type='hidden' name='isoUpPhone' id='isoUpPhone'  >";
            var countryDataUpPhone = window.intlTelInputGlobals.getCountryData(),
                inputUpPhone = document.querySelector("#phoneUpPhone");
            try {
                itiUpPhone.destroy();
            } catch (e) {
            }
            var itiUpPhone = window.intlTelInput(inputUpPhone, {
                initialCountry: "auto",
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode);
                    });
                },
                utilsScript: " <?php echo e(asset('assets/js/utils.js')); ?>" // just for formatting/placeholders etc
            });
            inputUpPhone.addEventListener('keyup', resetUpPhone);
            inputUpPhone.addEventListener('countrychange', resetUpPhone);
            for (var i = 0; i < countryDataUpPhone.length; i++) {
                var country = countryDataUpPhone[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
            document.querySelector("#phoneUpPhone").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
            var validMsg = document.querySelector("#valid-msg");
            var errorMsg = document.querySelector("#error-msg")
            inputUpPhone.addEventListener('blur', function () {
                // resetUpPhone();
                if (inputUpPhone.value.trim()) {
                    // console.log(itiUpPhone.isValidNumber());
                    if (itiUpPhone.isValidNumber()) {
                        // validMsg.classList.add("invisible");
                        $("#submit_phone").prop("disabled", false);
                    } else {
                        $("#submit_phone").prop("disabled", true);
                        inputUpPhone.classList.add("error");
                        var errorCode = itiUpPhone.getValidationError();
                        errorMsg.innerHTML = errorMap[errorCode];
                        errorMsg.classList.remove("invisible");
                    }
                }
            });
            resetUpPhone();
        }

        function resetUpPhone() {
            inputUpPhone.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("invisible");
            validMsg.classList.add("invisible");
            $("#submit_phone").prop("disabled", true);
            // input.classList.remove("error");
            // errorMsg.innerHTML = "";
            // errorMsg.classList.add("hide");
            // validMsg.classList.add("hide");
            // $("#submit_form").prop("disabled", false);
            var phone = itiUpPhone.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#phoneUpPhone").val();
            var countryData = itiUpPhone.getSelectedCountryData();
            phone = '00' + countryData.dialCode + phone;
            // $("#output").val(phone);
            $("#outputUpPhone").val(phone);
            // window.livewire.emit('changefullNumber', phone);
            // window.livewire.emit('changefullNumber');
            $("#ccodeUpPhone").val(countryData.dialCode);
            $("#isoUpPhone").val(countryData.iso2);

            fullphone = $("#outputUpPhone").val();
            if (inputUpPhone.value.trim()) {
                // console.log(itiAdd2Contact.isValidNumber());
                if (itiUpPhone.isValidNumber()) {
                    // validMsg.classList.add("invisible");
                    errorMsg.classList.add("invisible");
                    $("#submit_phone").prop("disabled", false);

                } else {
                    $("#submit_phone").prop("disabled", true);
                    inputUpPhone.classList.add("error");
                    var errorCode = itiUpPhone.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("invisible");
                }
            } else {
                $("#submit_phone").prop("disabled", true);
                inputUpPhone.classList.remove("error");
                var errorCode = itiUpPhone.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.add("invisible");
            }
        };
    });
</script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/layouts/master.blade.php ENDPATH**/ ?>